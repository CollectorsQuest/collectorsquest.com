<?php
/*
Plugin Name: Mail Queues by PBCI
Version: 1.0.5
Plugin URI: http://www.pyebrook.com/mail-queues/
Description: Send mail using SMTP without exceeding the the rate allowed by your mail provider(s). Mail can be sent using multiple user logins/passwords to give fault tolerance and make each mail message somewhat unique so that it is less likely to be flagged as SPAM/UCE by either your or the downstream mail providers.  Messages can be automatically reset when the plugin detects Non-delivery messages (NDRs) returned from mail sent by the plugin.  
Author: Pye Brook Company, Inc. / Jeffrey Schutzman 
Author URI: http://www.pyebrook.com
Donate URI: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JQ8L23G6D3ANJ

  Copyright 2011-2012  Pye Brook Company, Inc.  (email : jeff@pyebrook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/********************************************************
 * Get the POP3 class so we can do our bouce processing *
 * ******************************************************
*/
require_once( ABSPATH . WPINC . '/class-pop3.php' );


/**
 * This is the mail queue class
 *
 */

if (!class_exists('PBCIMailQueue')) {

	/**
	 * 
	 * Defines the mailqueues and the operations that can be used to manipilate them
	 * @author Jeff
	 *
	 */
	class PBCIMailQueue {
		
		/**
		 * 
		 * Log a call parameters to file to files
		 * @param unknown_type $args whatever is passed to this function is put into the log file
		 * @return the arguments that were passed
		 */
		function pbci_mail_log($args) {
			if ( isset($args) ) {
				$log_message = '>--- '.date('r').'  ';

				if ( !is_array($args) ) {
					$log_message .= $args;
				} else {
					$count = 0;
					foreach ( $args as $a ) {
						if ( !is_array($a) ) {
							if ( strlen($a) > 30 )
								$out = substr($a,0,30 ).'...';
							else
								$out = $a;

							if ( $count > 0 )
								$log_message .=  ', ';

							$log_message .= $out;
							$count += 1;
						}
					}
				}

				$log_message .= '\n';
				$this->pbci_append_log($log_message);
			}

		return $args;
		}

	
		/**
		 * 
		 * writes a log message into a file
		 * @param unknown_type $msg : the messages to write to the file
		 * @param unknown_type $queueid : an optional queue id that the message is applied to, changes the output file name
		 */
		function pbci_append_log($msg, $queueid = -1 ) {

			if ( !isset($queueid) || $queueid == -1 ) {
				$logfile = WP_CONTENT_DIR . '/mail.log';
			} else {
				$logfile = WP_CONTENT_DIR . '/mail-'.$queueid.'.log';
			}

	    	$fp = fopen($logfile, 'a+');
	    	fwrite($fp, $msg."\n");
	    	fclose($fp);

		}

		
		/*
		* Any queues that send an NDR are going to get disabled, stops the NDRs and redirects messages to other queues
		*/
		
		/**
		 * 
		 * disables a queue for a period of time
		 * @param integer $queue_id : id within the database row that uniquely identofoes the queue
		 * @return unknown
		 */
		function disable_queue( $queue_id, $error=false ) {
			$result = false;
			
			if ( isset($queue_id) && !empty($queue_id) ) {			
				global $wpdb;
				if ( $error )
					$sql = 'UPDATE `'.$this->control_table.'` SET disabled_until=date_add(NOW(),INTERVAL 1 hour), enabled=0, errors=errors+1 WHERE queue_id="'.$queue_id.'"';
				else
					$sql = 'UPDATE `'.$this->control_table.'` SET disabled_until=date_add(NOW(),INTERVAL 1 hour), enabled=0 WHERE queue_id="'.$queue_id.'"';
				
				$rows_updated = $wpdb->query($sql);
				
				if ( $rows_updated == 1 )
					$result = true; // that's what supposed to happen
			}
			
			return $result;
		}

		/**
		 * 
		 * Turns a queue back on so email can be sent through it
		 * @param unknown_type $queue_id
		 * @return boolean
		 */
		function enable_queue( $queue_id ) {
			$result = false;
				
			if ( isset($queue_id) && !empty($queue_id) ) {
				global $wpdb;
				$sql = 'UPDATE `'.$this->control_table.'` SET disabled_until=0, enabled=1 WHERE queue_id="'.$queue_id.'"';
		
				$rows_updated = $wpdb->query($sql);
		
				if ( $rows_updated == 1 )
					$result = true; // that's what supposed to happen
			}
				
			return $result;
		}
		
		
		
		/**
		 * 
		 * resends a message presumably because of an error, all fiields from the database
		 * @param string $smtp_message_id : the smtp message id of the messsage to unsend
		 */
		function resend_message( $smtp_message_id ) {
			$result = false;
			
			if ( isset($smtp_message_id) && !empty($smtp_message_id) ) {			
				global $wpdb;
								
				$sql = "INSERT INTO ".$this->queue_table." (`queue_dt`,`msg_to`,subject,`msg_from`,`message`,`headers`,`attachments`,`attempts`, `recipients`)"
				." (SELECT `queue_dt` ,`msg_to` ,`subject`, `msg_from`, `message`, `headers`, `attachments`, `attempts`, `recipients` "
				." FROM ".$this->queue_table." WHERE `smtp_msg_id`='".$smtp_message_id."')";

				$rows_updated = $wpdb->query($sql);
				
				if ( $rows_updated == 1 )
					$result = true; // that's what supposed to happen				
			}						
			
			return $result;
		}
		
		function update_sent_counters() {
			global $wpdb;

			// update last hour counters
			$sql = 	'UPDATE '.$this->control_table.' SET sent_last_day=0,sent_last_hour=0';
			$rows_updated = $wpdb->query($sql);
					
			$sql = 	'UPDATE '.$this->control_table.' '
					.' INNER JOIN ( SELECT queue_id, sum(recipients) as sent ' 
					.' FROM '.$this->queue_table.' WHERE'
					.' (`'.$this->queue_table.'`.`sent_dt` > (now() - interval 1 hour)) GROUP BY queue_id) AS thecounts '
					.' ON '.$this->control_table.'.queue_id=thecounts.queue_id '
					.' SET '.$this->control_table.'.sent_last_hour=thecounts.sent ';
		
			$rows_updated = $wpdb->query($sql);

			// update last day counters
			$sql = 'UPDATE '.$this->control_table.' '
					.' INNER JOIN (SELECT queue_id, sum(recipients) as sent '
					.' FROM '.$this->queue_table.' '
					.' WHERE (`'.$this->queue_table.'`.`sent_dt` > (now() - interval 1 day)) GROUP BY queue_id) AS thecounts '
					.' ON '.$this->control_table.'.queue_id=thecounts.queue_id '
					.' SET '.$this->control_table.'.sent_last_day=thecounts.sent';
			
			$rows_updated = $wpdb->query($sql);
			
			/*
			* Enable any queues that were disabled previously and are ready to be used again
			*/
			$sql = 'UPDATE `'.$this->control_table.'`  SET enabled= 1 WHERE (`disabled_until` < now()) and (enabled =0)';
			$result = $wpdb->query($sql);
			
			/*
			 * Any queues that are at thier limit need to be disabled for a while
			*/
			$sql = 'UPDATE `'.$this->control_table.'` SET disabled_until=date_add(NOW(),INTERVAL '
					.$this->pbci_queue_options['pbci_mail_queue_disable_minutes'].' MINUTE), enabled=0 WHERE `enabled`=1 AND'
					.' ((`sent_last_hour` >= `max_per_hour`) OR (`sent_last_day` >= `max_per_day`))';
			
			$result = $wpdb->query($sql);
								
			return true;
		}		
		
		 /**
		  * 
		  * Take messages from the queue and send them on thier way
		  * @return boolean
		  */
		 function pbci_mail_dequeue() {
			global $wpdb;
			$dequeueCount = 0;

				/*
				 * See if there aare messages to resend or queues to disable
				 */
				$this->check_non_deliveries();
				$this->update_sent_counters();
				
				if ( $this->pbci_queue_options['pbci_mail_disable_dequeue'] == true ) {
					return true;
				}
				
				/*
				 * get a mail queue from the status view, if no queue is returned that means that all of the configured
				 * queues are disabled
				 */
				$sql = 'SELECT * FROM `'.$this->control_table.'` WHERE enabled=1 ORDER BY errors ASC, sent_last_hour DESC';
				$pbci_smtp_queues = $wpdb->get_results($sql, ARRAY_A );

				$sql = 'SELECT count(*) as sent  FROM `'.$this->queue_table.'` WHERE sent=0 AND attempts<'.$this->pbci_queue_options['pbci_mail_max_send_attempts'];
				$unsentcount = $wpdb->get_var( $wpdb->prepare( $sql ) );
				if ( $unsentcount == 0 ) {
					// no messages to send
					return true;
				}

				
				foreach ($pbci_smtp_queues as $q ) {
					$this->pbci_smtp_queue = $q;
										
					// set the queue send error ticker back to 0
					$queue_send_error_ticker = 0;
						
					if ( $this->pbci_smtp_queue != NULL ) {
						// keep mail batch sizes reasonable to avoid hangs or mail bursts
						$max_batch = intval(($this->pbci_smtp_queue['max_per_hour'] - $this->pbci_smtp_queue['sent_last_hour']) / 2);
						if ( $max_batch == 0 ) {
							$max_batch = 1;
						} elseif ($max_batch > $this->pbci_queue_options['pbci_mail_max_batch']) {
							$max_batch = $this->pbci_queue_options['pbci_mail_max_batch'];
						}

						if ( $max_batch > 0 ) {
							/*
							 * Grab some messages to send, order the messages by the sent date time and the queue date date.
							 * Ordering by the sent date time means that messages that tried to send but failed will move to
							 * the end of the queue.
							 */
							$sql = 'SELECT * FROM `'.$this->queue_table.'` WHERE sent=0 AND attempts<'.$this->pbci_queue_options['pbci_mail_max_send_attempts'].' ORDER BY sent_dt,queue_dt LIMIT '.$max_batch;
							$msg_list = $wpdb->get_results($sql, ARRAY_A );

							$errors = $this->pbci_smtp_queue['errors'];
							$queue_id = $this->pbci_smtp_queue['queue_id'];
							
							// set the queue send error ticker back to 0
							$queue_send_error_ticker = 0;								

							foreach ( $msg_list as $row ) {
								$id = $row['id'];
								$to = $row['msg_to'];
								$subject = $row['subject'];
								$message = $row['message'];
								$headers = unserialize($row['headers']);
								$attachments = unserialize($row['attachments']);
								$attempts  = $row['attempts']+1;
								$the_time=date('Y-m-d H:i:s', time());
								$messageid = $this->unique_message_id();

								// send the message using the current queue
								$mail_send_ok = $this->wp_mail_it( $to, $subject, $message, $headers, $attachments, $messageid );

								
								// if the result of the mail send is ok (true means no error) then update the sent queue accordingly, otherwise update the queue and the control
								if ( $mail_send_ok == true ) {
									$result = $wpdb->update(
										''.$this->queue_table.'',
										array( 'sent' => '1', 'sent_dt' => $the_time, 'queue_id' => $queue_id, 'smtp_msg_id' => $messageid, 'attempts' => $attempts ),
										array( 'id' => $id )
									);

									if ( $result != 1 ) {
										//that's a problem, database issue? what to do?
									}

									// set the queue send error ticker back to 0
									$queue_send_error_ticker = 0;
										
									/*
									 * Update queue control updating the error count
									 */
									$result = $wpdb->update( ''.$this->control_table.'', array( 'last_success' => $the_time ), array( 'queue_id' => $queue_id ) );
									if ( $result != 1 ) {
										//that's a problem, database issue? what to do?
									}
								} else {
									/*
									 * Update the message queue with the send attempt
									 */
									$result = $wpdb->update(
										''.$this->queue_table.'',
										array( 'sent' => '0', 'sent_dt' => $the_time, 'queue_id' => $queue_id, 'attempts' => $attempts ),
										array( 'id' => $id )
									);
									if ( $result != 1 ) {
										//that's a problem, database issue? what to do?
									}

									/*
									 * Update queue control updating the error count
									 */
									$errors++;
									$result = $wpdb->update( ''.$this->control_table.'', array( 'errors' => $errors, 'last_failure' => $the_time ), array( 'queue_id' => $queue_id ) );
									if ( $result != 1 ) {
										//that's a problem, database issue? what to do?
									}

									/*
									 * Do we want to disable the queue because of consecutive errors
									 */
									$queue_send_error_ticker++;
									if ( $queue_send_error_ticker > $this->pbci_queue_options['pbci_mail_max_send_attempts']) {
										$disabled_until = date('Y-m-d H:i:s', time()+$this->pbci_queue_options['pbci_mail_dequeue_wait']);
										$result = $wpdb->update( ''.$this->control_table.'', array( 'enabled'=>0, 'disabled_until' => $disabled_until ), array( 'queue_id' => $queue_id ) );
										if ($result != 1 ) {
											//that's a problem, database issue? what to do?
										}
									}
								}

								$dequeueCount++;
							}
						}
					}
				}

				$this->verify_queue_cronjob();
									
				return true;
		}

		/**
		 * 
		 * Add message to the mail queue
		 * @param unknown_type $to
		 * @param unknown_type $subject
		 * @param unknown_type $message
		 * @param unknown_type $headers
		 * @param unknown_type $attachments
		 */
		function pbci_mail_queue( $to, $subject, $message, $headers, $attachments ) {
	    	global $wpdb;

	    	$recipient_count = $this->recipient_count( $to, $subject, $message, $headers );
	    	$s_headers = serialize($headers);
	    	$s_attachments = serialize($attachments);	    	
	    	
	    	$result = $wpdb->insert(
				''.$this->queue_table.'', array( 'msg_to' => $to, 'subject' => $subject, 'message' => $message, 'headers' => $s_headers, 'attachments' => $s_attachments, 'recipients' => $recipient_count ),
				array( '%s', '%s', '%s', '%s', '%s', '%s' )
			);

	    	$this->verify_queue_cronjob();	    	

	    	return $result;
		}

		/**
		 * 
		 * Displays the manage page for the plugin.
		 */
		function pbci_mail_queue_manager() {
	        global $wpdb;

	        $this->update_sent_counters();

	        /*
	        * See if there aare messages to resend or queues to disable
	        */
	        $this->check_non_deliveries();
	        	         
	        $showPasswords = false;

	        ?><div class="wrap"><?php
	        
	        echo '<h2>'; _e( 'Message Queue Status', 'pbci-mail' ); echo '</h2>';
	        
			$sql = 'SELECT count(*) as sent  FROM `'.$this->queue_table.'` WHERE sent=0 AND attempts<'.$this->pbci_queue_options['pbci_mail_max_send_attempts'];
			$count = $wpdb->get_var( $wpdb->prepare( $sql ) );
			
			$no = __('No');
			
			if ( $count == false ) {
				$count = $no;
				$unsentcount = 0;
			} else {
				$unsentcount = $count;
			}

			$this->verify_queue_cronjob();
			
	        echo '<p>'.$count.' unsent messages in queue ready to send</p>';
				
			$sql = 'SELECT count(*) as sent  FROM `'.$this->queue_table.'` WHERE sent=0 AND attempts>='.$this->pbci_queue_options['pbci_mail_max_send_attempts'];
			$count = $wpdb->get_var( $wpdb->prepare( $sql ) );
			if ( $count == false ) $count = $no;
			echo '<p>'.$count.__(' unsent messages in queue not being sent becuase of too many failed attempts').'</p>';				
			
			$sql 	= 'select count(*) from `'.$this->queue_table.'` where (`'.$this->queue_table.'`.`queue_dt` < (now() - interval 8 hour)) AND (sent=0)';
			$count 	= $wpdb->get_var( $wpdb->prepare( $sql ) );
			if ( $count == false ) $count = $no;
			echo '<p>'.$count.__(' messages queued for sending more than 8 hours ago remain unsent').'</p>';

			$sql 	= 'select count(*) from `'.$this->queue_table.'` where (`'.$this->queue_table.'`.`sent_dt` > (now() - interval 10 minute)) AND (sent=1)';
			$count 	= $wpdb->get_var( $wpdb->prepare( $sql ) );
			if ( $count == false ) $count = $no;
			echo '<p>'.$count.__(' messages sent in the last 10 minutes').'</p>';

			$sql 	= 'select count(*) from `'.$this->queue_table.'` where (`'.$this->queue_table.'`.`queue_dt` > (now() - interval 10 minute))';
			$count 	= $wpdb->get_var( $wpdb->prepare( $sql ) );
			if ( $count == false ) $count = $no;
			echo '<p>'.$count.__(' messages queued for sending in the last 10 minutes').'</p>';

			$sql 	= 'select count(*) from `'.$this->queue_table.'` where (`'.$this->queue_table.'`.`queue_dt` > (now() - interval 1 hour))';
			if ( $count == false ) $count = $no;
			if ( $unsentcount == false ) $unsentcount = $no;
			echo '<p>'.$count.__(' messages queued for sending in the last hour').'</p>';

			$sql 	= 'select count(*) from `'.$this->queue_table.'` where (`'.$this->queue_table.'`.`queue_dt` > (now() - interval 1 day))';
			$count 	= $wpdb->get_var( $wpdb->prepare( $sql ) );
			if ( $count == false ) $count = $no;
			echo '<p>'.$count.__(' messages queued for sending in the last 24 hours').'</p>';

			$sql 	= 'select count(*) from `'.$this->queue_table.'` where (`'.$this->queue_table.'`.`queue_dt` between (now() - interval 1 day) AND (now() - interval 2 day))';
			$count 	= $wpdb->get_var( $wpdb->prepare( $sql ) );
			if ( $count == false ) $count = $no;
			echo '<p>'.$count.__(' messsages queued for sending in the period 24-48 hours ago').'</p>';

			$timestamp = wp_next_scheduled( $this->cron_action );

			if ( ($timestamp == false) ) {
				echo '<p>'.__('No schedule for next message de-queue').'</p>';
			} else {
				$secondsleft = max( 0, $timestamp - time() );
				echo '<p>'.__('Message queues will be checked again ').$secondsleft.__(' seconds from now.  That is after ').date( 'F j, Y, g:i:s', $timestamp ).'</p>';
			}

	        // get a mail queues from the status view
			$sql = 'SELECT * FROM `'.$this->control_table.'` ORDER BY `queue_id`';
			$pbci_smtp_queues = $wpdb->get_results( $sql, ARRAY_A );

	        $doing_edit = (isset( $_GET['action']) && $_GET['action'] == 'edit-cron') ? $_GET['id'] : false ;
	        ?>
	        <h2><?php _e( 'SMTP Mail Queues', 'pbci-mail' ); ?></h2>
	        <p></p>
	        <table class="widefat">
	        <thead>
	            <tr>
	                <th><?php _e('Queue ID', 'pbci-mail'); ?></th>
	                <th><?php _e('Last Hour', 'pbci-mail'); ?></th>
	                <th><?php _e('Last Day', 'pbci-mail'); ?></th>
	                <th><?php _e('Server', 'pbci-mail'); ?></th>
	                <th><?php _e('login', 'pbci-mail'); ?></th>
	                <?php if ( $showPasswords ) { echo '<th>';echo _e('password', 'pbci-mail'); echo '</th>'; }?>
	                <th><?php _e('ssl', 'pbci-mail'); ?></th>
	                <th><?php _e('Last Success', 'pbci-mail'); ?></th>
	                <th><?php _e('Last Failure', 'pbci-mail'); ?></th>
	                <th><?php _e('Errors', 'pbci-mail'); ?></th>
	                <th><?php _e('Status', 'pbci-mail'); ?></th>
	            </tr>
	        </thead>
	        <tbody>
	        <?php   
	        if( empty($pbci_smtp_queues) ) {
	            echo '<tr colspan="11"><td>';_e('You currently have no mail queues. ', 'pbci-mail');echo '</td></tr>';
	        } else {
	            $class = '';

	            foreach( $pbci_smtp_queues as $queue ) {
	                if( $doing_edit && $doing_edit==$hook && $time == $_GET['next_run'] && $sig==$_GET['sig'] ) {
	                    $doing_edit = array('hookname'=>$hook,
	                    'next_run'=>$time,
	                    'schedule'=>($data['schedule'] ? $data['schedule'] : '_oneoff'),
	                    'sig'=>$sig,
	                	'args'=>$data['args']);
	                }

	                echo '<tr id=\"q-'.$queue['queue_id'].'\" class=\"$class\">';
	                echo '<td>'.$queue['queue_id'].'</td>';
	                echo '<td>'.$queue['sent_last_hour'].'/'.$queue['max_per_hour'].'</td>';
	                echo '<td>'.$queue['sent_last_day'].'/'.$queue['max_per_day'].'</td>';
					echo '<td>'.$queue['server'].'</td>';
	                echo '<td>'.$queue['login'].'</td>';
	                if ( $showPasswords ) { echo '<td>'.$queue['password'].'</td>'; }
	                echo '<td>'.$queue['ssl'].'</td>';
	                echo '<td>'.$queue['last_success'].'</td>';
	                echo '<td>'.$queue['last_failure'].'</td>';
	                echo '<td>'.$queue['errors'].'</td>';
	                echo '<td>';
	                if ( $queue['enabled'] ) {
	                	echo _e('Ready', 'pbci-mail');
	                } else {
	                	echo _e('Off Until', 'pbci-mail').' '.$queue['disabled_until'];
	                }
	                echo '</td></tr>';
	            }
	        }
	        

        ?></tbody></table></div><?php      
        please_donate();
		}

    	/**
    	 * 
    	 * Sends the mail message - does little processsing but gives a good catch point for
    	 * debugging and altering the call in test mode
    	 * 
    	 * @param unknown_type $to
    	 * @param unknown_type $subject
    	 * @param unknown_type $message
    	 * @param unknown_type $headers
    	 * @param unknown_type $attachments
    	 * @param unknown_type $messageid
    	 * @return boolean true if mail sent successfully, false if an error
    	 */
	    function wp_mail_it( $to, $subject, $message, $headers = '', $attachments = array(), $messageid=NULL ) {

			$testAddress = $this->pbci_queue_options['pbci_mail_debug_mode_address'];

			if ( $this->testmode ) {
			    $originalTo = $to;
			    $to = $testAddress;
			    $subject = '('.$originalTo.') '.$subject;
			}

			if ( $this->testmode ) {
				$this->pbci_append_log('>--- Overrode TO, instead of '.$originalTo.' used address '.$testAddress );
		   	}

			$result = $this->send( $to, $subject, $message, $headers, $attachments, $messageid );

			return $result;
		}

		function unique_message_id() {
			if ( isset($_SERVER['SERVER_NAME'] ) ) {
		      	$servername = $_SERVER['SERVER_NAME'];
		    } else {
		      	$servername = 'localhost.localdomain';
		    }

		    $uniq_id = md5(uniqid(time()));
		    $result = sprintf('%s@%s', $uniq_id, $servername);

		    return $result;
		}

		function send( $to, $subject, $message, $headers = '', $attachments = array(), $messageid) {

			$testAddress = $this->pbci_queue_options['pbci_mail_debug_mode_address'];

			/*
			 *  Compact the input, try to be nice and apply the filters, and extract them back out
			 */
			extract( apply_filters( 'wp_mail', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) ) );

		 	/*
		 	 * Make sure atttachments is an array
		 	 */
			if ( !empty($attachments ) )
				if ( !is_array($attachments) )
					$attachments = explode( '\n', str_replace( '\r\n', '\n', $attachments ) );


			/*
			 * Go through the headers and look for values to use within the message envelope and
			 * message body (Originally from the phpmailer puggable_wp_mail function - look thier for credits)
			 */
			if ( empty( $headers ) ) {
				$headers = array();
			} else {
				if ( !is_array( $headers ) ) {
					// Explode the headers out, so this function can take both
					// string headers and an array of headers.
					$headers = str_replace( "\r\n", "\n", $headers );
					$tempheaders = explode( "\n",  $headers );
				} else {
					$tempheaders = $headers;
				}
				$headers = array();

				// If it's actually got contents
				if ( !empty( $tempheaders ) ) {
					// Iterate through the raw headers
					foreach ( (array) $tempheaders as $header ) {
						if ( strpos($header, ':') === false ) {
							if ( false !== stripos( $header, 'boundary=' ) ) {
								$parts = preg_split('/boundary=/i', trim( $header ) );
								$boundary = trim( str_replace( array( '\'', '"' ), '', $parts[1] ) );
							}
							continue;
						}
						// Explode them out
						list( $name, $content ) = explode( ':', trim( $header ), 2 );

						// Cleanup crew
						$name    = trim( $name    );
						$content = trim( $content );

						switch ( strtolower( $name ) ) {
							// Mainly for legacy -- process a From: header if it's there
							case 'from':
								if ( strpos($content, '<' ) !== false ) {
									// So... making my life hard again?
									$from_name = substr( $content, 0, strpos( $content, '<' ) - 1 );
									$from_name = str_replace( '"', '', $from_name );
									$from_name = trim( $from_name );

									$from_email = substr( $content, strpos( $content, '<' ) + 1 );
									$from_email = str_replace( '>', '', $from_email );
									$from_email = trim( $from_email );
								} else {
									$from_email = trim( $content );
								}
								break;
							case 'content-type':
								if ( strpos( $content, ';' ) !== false ) {
									list( $type, $charset ) = explode( ';', $content );
									$content_type = trim( $type );
									if ( false !== stripos( $charset, 'charset=' ) ) {
										$charset = trim( str_replace( array( 'charset=', '"' ), '', $charset ) );
									} elseif ( false !== stripos( $charset, 'boundary=' ) ) {
										$boundary = trim( str_replace( array( 'BOUNDARY=', 'boundary=', '"' ), '', $charset ) );
										$charset = '';
									}
								} else {
									$content_type = trim( $content );
								}
								break;
							case 'cc':
								$cc = array_merge( (array) $cc, explode( ',', $content ) );
								break;
							case 'bcc':
								$bcc = array_merge( (array) $bcc, explode( ',', $content ) );
								break;
							default:
								// Add it to our grand headers array
								$headers[trim( $name )] = trim( $content );
								break;
						}
					}
				}
			}

			/*
			 * Done with headers at this point we should know if we have $content_type, $bcc, $cc,
			 * $from_email and $from_name
			 *
			 */

			/*
			 * We'll be needing a PHPMailer thingy.  We'll use a local one so nobody else can stomp on us,  bad
			 * news is we are not allowing others to override us either :(
			 */// (Re)create it, if it's gone missing
			global $phpmailer;

			// (Re)create it, if it's gone missing
			if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) {
				require_once ABSPATH . WPINC . '/class-phpmailer.php';
				require_once ABSPATH . WPINC . '/class-smtp.php';
				$phpmailer = new PHPMailer();
			}

			/*
			 * Make sure the mailer thingy is clean before we start,  should not
			 * be necessary, but who knows what others are doing to our mailer
			 */
			$phpmailer->ClearAddresses();
			$phpmailer->ClearAllRecipients();
			$phpmailer->ClearAttachments();
			$phpmailer->ClearBCCs();
			$phpmailer->ClearCCs();
			$phpmailer->ClearCustomHeaders();
			$phpmailer->ClearReplyTos();

			
			/********************************************************************************
			************* Do message body work first, headers and recipients later **********
			*******************************************************************************/
			// Set mail's subject and body
			$phpmailer->Subject = $subject;
			$phpmailer->Body    = $message;

			// Set Content-Type and charset
			// If we don't have a content-type from the input headers
			if ( !isset( $content_type ) )
			$content_type = 'text/plain';
			
			$content_type = apply_filters( 'wp_mail_content_type', $content_type );
			
			$phpmailer->ContentType = $content_type;
			
			// Set whether it's plaintext, depending on $content_type
			if ( 'text/html' == $content_type )
			$phpmailer->IsHTML( true );
			
			// If we don't have a charset from the input headers
			if ( !isset( $charset ) )
			$charset = get_bloginfo( 'charset' );
			
			// Set the content-type and charset
			$phpmailer->CharSet = apply_filters( 'wp_mail_charset', $charset );

			/**
			* We'll let php init mess with the message body and headers.  But then
			* we stomp all over it.  Sorry, my plug-inis more important than yours :)
			*/
			do_action_ref_array( 'phpmailer_init', array( &$phpmailer ) );
							
			/********************************************************************************
			 * ******************************************************************************
			 *******************************************************************************/
						
			
			/**
			 * Now it's time to setup the headers and recipients
			 */
			
			/*
			 * the email 'from' name can be anything, if we don't have one use the default
			 * If we have a name extracted from the headers change it so that recipients can
			 * tell the email is from the web site rather than the person directly
			 */
			if ( !isset( $from_name ) ) {
				$from_name =  $this->pbci_queue_options['pbci_mail_from_name']; //pbci_default_from_name;
			} else {
				$original_from_name = $from_name;
				$from_name =  $from_name.' via '.$this->pbci_queue_options['pbci_mail_from_name']; //pbci_default_from_name;;
			}

			/*
			 * plugins can override the from email and name, let's give them a chaance, but
			 */
			//$newFromEmail = apply_filters( 'wp_mail_from'     , $from_email );
			$newFromName = apply_filters( 'wp_mail_from_name' , $from_name  );

			//if ( $newFromEmail !== $from_email ) {
			// $this->pbci_append_log('From email changed by other plugin.  '.$from_email.' => '.$newFromEmail );
				//$from_email = $newFromEmail;
			//}

			if ( $newFromName !== $from_name ) {
				$this->pbci_append_log('From email changed by other plugin.  '.$from_name.' => '.$newFromName );
				$from_name = $newFromName;
			}

			/*
			 * We are always going to use the information from the login queue so we don't
			 * give either our mail provider or the recipients mail providers an additional reason
			 * to mark our message as spam.  Sometimees they check to see if the from email
			 * matches the sending account, mail services, mail server, etc.
			 *
			 *  What we can do is add the desired from email to the reply to header so that when
			 *  the recipient gets the email they can respond to the correct address.
			 */
			if ( isset( $from_email ) ) {
				if ( !isset($original_from_name))
					$original_from_name = $from_email;
				$phpmailer->AddReplyTo($from_email,$original_from_name);
			}
			
			if ( $this->pbci_queue_options['pbci_mail_set_reply_to'] ) {
				$phpmailer->AddReplyTo($this->pbci_queue_options['pbci_mail_reply_to_email'],$this->pbci_queue_options['pbci_mail_reply_to_name']);
				
			}

			$from_email = $this->pbci_smtp_queue['login'];

			/*
			 * Normally we would want the wp_mail_from and wp_mail_from_name filters to be called,
			 * instead we are going to apply our own rules and set the from email to the email matching
			 * the mail queue.  This done in an effort to give our email a better chance at avoiding
			 * false spam detection.
			 */

			//$phpmailer->From     = apply_filters( 'wp_mail_from'     , $from_email );
			$phpmailer->From = $from_email;

			//$phpmailer->FromName = apply_filters( 'wp_mail_from_name', $from_name  );
			$phpmailer->FromName = $from_name;

			/*
			 * The message has to be sent to all of the people in the $to array
			 */
			if ( !is_array( $to ) )
				$to = explode( ',', $to );

			foreach ( (array) $to as $recipient ) {
				if ( $this->testmode ) {
					$phpmailer->AddAddress( trim( $testAddress ) );
				} else {
					$phpmailer->AddAddress( trim( $recipient ) );
				}
			}

			// Add any CC and BCC recipients
			if ( !empty( $cc ) ) {
				foreach ( (array) $cc as $recipient ) {
					if ( $this->testmode ) {
						$phpmailer->AddCc( trim( $testAddress ) );
					} else {
						$phpmailer->AddCc( trim($recipient) );
					}
				}
			}

			if ( !empty( $bcc ) ) {
				foreach ( (array) $bcc as $recipient) {
					if ( $this->testmode ) {
						$phpmailer->AddBcc( $testAddress );
					} else {
						$phpmailer->AddBcc( trim($recipient) );
					}
				}
			}

			// We are sending SMTP mail
			$phpmailer->IsSMTP();

			// Set custom headers
			if ( !empty( $headers ) ) {
				foreach( (array) $headers as $name => $content ) {
					$phpmailer->AddCustomHeader( sprintf( '%1$s: %2$s', $name, $content ) );
				}
				if ( false !== stripos( $content_type, 'multipart' ) && ! empty($boundary) )
					$phpmailer->AddCustomHeader( sprintf( 'Content-Type: %s;\n\t boundary=\"%s\"', $content_type, $boundary ) );
			}

			if ( !empty( $attachments ) ) {
				foreach ( $attachments as $attachment ) {
					$phpmailer->AddAttachment($attachment);
				}
			}

			if ( $this->pbci_smtp_queue['server'] == '' )
				return false; // can't have a blank server

			if ( $this->pbci_smtp_queue['port'] == '' )
				return false; // can't have a blank port

			// Set the mailer type as per config above, this overrides the already called isMail method
			$phpmailer->Mailer = 'smtp';

			// Set the Sender (return-path) if required
			//if (get_option('mail_set_return_path'))
			//	$phpmailer->Sender = $phpmailer->From;

			// Set the SMTPSecure value, if set to none, leave this blank
			$phpmailer->SMTPSecure = $this->pbci_smtp_queue['ssl'] == 'none' ? '' : $this->pbci_smtp_queue['ssl'];

			// Set the other options
			$phpmailer->Host = $this->pbci_smtp_queue['server'];
			$phpmailer->Port = $this->pbci_smtp_queue['port'];

			if ( isset($messageid) && ($messageid != NULL) && $messageid != '') {
				$phpmailer->MessageID = $messageid;
				$phpmailer->AddCustomHeader( sprintf( 'X-PBCI-MID: %s',$messageid ) );
				$phpmailer->AddCustomHeader( sprintf( 'X-PBCI-Q: %s',$this->pbci_smtp_queue['queue_id'] ) );
			}

			if ( $this->pbci_smtp_queue['login'] ) {
				// If we're using smtp auth, set the username & password
				$phpmailer->SMTPAuth = TRUE;
				$phpmailer->Username = $this->pbci_smtp_queue['login'];
				$phpmailer->Password = $this->pbci_smtp_queue['password'];
			}

			// Set SMTPDebug to 2 will collect dialogue between us and the mail server
			$phpmailer->SMTPDebug = 2;

			// Start output buffering to grab smtp output
			ob_start(); 

			// Send!
			$result = true; // start with true, meaning no error
			$result = @$phpmailer->Send();
			$phpmailer->SMTPClose();

			// Grab the smtp debugging output
			$smtp_debug = ob_get_clean();

			if ( ( $result != true ) || $this->pbci_queue_options['pbci_mail_log_all_mail'] ) {
				$hostmsg = 'host: '.($phpmailer->Host).'  port: '.($phpmailer->Port).'  secure: '.($phpmailer->SMTPSecure) .'  auth: '.($phpmailer->SMTPAuth).'  user: '.($phpmailer->Username)."  pass: *******\n";
			    $msg = '';
				$msg .= 'The result was: '.$result."\n";
			    $msg .= 'The mailer error info: '.$phpmailer->ErrorInfo."\n";
			    $msg .= $hostmsg;
			    $msg .= "The SMTP debugging output is shown below:\n";
			    $msg .= $smtp_debug."\n";
			    $msg .= 'The full debugging output(exported mailer) is shown below:\n';
			    $msg .= var_export($phpmailer,true)."\n";
				$this->pbci_append_log($msg,$this->pbci_smtp_queue['queue_id']);								
			}

			$this->last_send_smtp_debug = $smtp_debug;
			
			return $result;
		}

		/**
		 * get and save options
		 */
		function get_admin_options() {
											    
		    $avaluethatshouldneverbestored = 1234567890;
			foreach ($this->pbci_queue_options as $key => $defaultvalue) {
				add_option($key,$defaultvalue); // does nothing if option exists
				$this->pbci_queue_options[$key] = get_option($key,$defaultvalue);
			}

			if ( isset($_SERVER['SERVER_NAME'] ) ) {
				$servername = $_SERVER['SERVER_NAME'];
				if ( preg_match('/'.$this->pbci_queue_options['pbci_mail_debug_mode_server'].'/i', $servername) ) {
					$this->testmode = true;
				}
			} else {
				$servername = 'localhost.localdomain';
			}
							
			
			return;
		}
		
		/**
		 * 
		 * Setup for displaying the options pages
		 */
		function register_mysettings() { // whitelist options
						
			foreach ($this->pbci_queue_options as $key => $option) {
				register_setting( $this->admin_options_name, $key );
			}
				
		}

		function mail_queue_admin_pages() {
			
			add_menu_page(								'Mail Queues', 			'Mail Queues', 			'manage_options',	'pbci_mail_queues',			array(&$this,'pbci_mail_queue_manager') );
			add_submenu_page('pbci_mail_queues', 		'Settings', 			'Settings', 			'manage_options', 	'pbci_mail_settings', 		array(&$this,'options_page'));
			add_submenu_page('pbci_mail_queues', 		'Stalled Mail', 		'Stalled Mail',			'manage_options', 	'pbci_mail_stalled', 		array(&$this,'stalled_mail'));
			add_submenu_page('pbci_mail_queues', 		'Test Mail Queueing',	'Test Mail Queueing', 	'manage_options', 	'pbci_mail_test', 			array(&$this,'test_messages'));
			add_submenu_page('pbci_mail_queues', 		'Setup Queues',			'Setup Queues',		 	'manage_options', 	'pbci_mail_queue_setup', 	array(&$this,'pbci_mail_queue_setup'));
			
			if ( $this->testmode ) {			
				add_submenu_page('pbci_mail_queues', 'Force Dequeue', 	'Force Dequeue',	'manage_options', 	'pbci_mail_dequeue', 	array(&$this,'pbci_mail_dequeue'));
				add_submenu_page('pbci_mail_queues', 'Check NDR',			'Check NDR',		'manage_options',	'pbci_mail_check_ndr',	array(&$this,'check_non_deliveries'));
			}				
		}

		/**
		 * 
		 * Display an option page listing mail that was not successfully sent, give user option to delete or
		 * requeue
		 */
		function stalled_mail() {
			global $wpdb;

			// Send a test mail if necessary
			if (isset($_POST['wpms_action']) && ($_POST['wpms_action'] == __('Delete Stalled Email', 'pbci-mail') )  ) {

				$sql = 'DELETE FROM `'.$this->queue_table.'` WHERE sent=0 AND attempts>='.$this->pbci_queue_options['pbci_mail_max_send_attempts'];
				$result = $wpdb->query($sql);
				
				// Output the response
				?>
							<div id="message" class="updated fade"><p><strong><?php _e('Success!', 'pbci-mail'); ?></strong></p>
							<p><?php _e("All stalled mail ($result messages) was deleted!", 'pbci-mail'); ?></p>
							</div>
							<?php						
			}
						
			
			
			
			$sql = 'SELECT * FROM `'.$this->queue_table.'` WHERE sent=0 AND attempts>='.$this->pbci_queue_options['pbci_mail_max_send_attempts'].' ORDER BY queue_dt,sent_dt';
			$msg_list = $wpdb->get_results($sql, ARRAY_A );

			?>
		        <div>
		        <h2><?php _e( 'Stalled Mail', 'pbci-mail' ); ?></h2>
		        <p></p>
		        <table class="widefat">
		        <thead>
		            <tr>
		                <th><?php _e('ID', 'pbci-mail'); ?></th>
		                <th><?php _e('Queued', 'pbci-mail'); ?></th>
		                <th><?php _e('Failures', 'pbci-mail'); ?></th>			                
		                <th><?php _e('To', 'pbci-mail'); ?></th>
		                <th><?php _e('Subject', 'pbci-mail'); ?></th>
		            </tr>
		        </thead>
		        <tbody>
	        <?php
	           
	        if( empty($msg_list) ) {
	            echo '<tr colspan="5"><td>';_e('You currently have no stalled mail. ', 'pbci-mail');echo '</td></tr>';
	        } else {
	            foreach ( $msg_list as $row ) {
	            	$id = $row['id'];
	            	$to = $row['msg_to'];
	            	$subject = $row['subject'];
	            	$attempts  = $row['attempts'];
	            	$queue_dt = $row['queue_dt']; 
	            	
	                echo '<tr>';
	                echo '<td>'.$id.'</td>';
	                echo '<td>'.$queue_dt.'</td>';
	                echo '<td>'.$attempts.'</td>';
	                echo '<td>'.$to.'</td>';
	                echo '<td>'.$subject.'</td>';
	                echo '</tr>';
	            }
	        }			        
		        ?></tbody></table></div>
		        
		        <?php 
		        if( !empty($msg_list) ) { ?>
		                
			        <form method="POST">
			        
			        <?php
			        if ( function_exists('wp_nonce_field') )
			        	wp_nonce_field('pbci-mail-test');
			        ?>
			        									
						<p class="submit">
	    					<input type="submit" name="wpms_action" id="wpms_action" class="button-primary" value="<?php _e('Delete Stalled Email', 'pbci-mail'); ?>" />
	        			</p>
	        		</form>
			        		        
			        <?php 
		        }
		        
		    please_donate();
			return;
		}

		
		
		/**
		 * 
		 * Display an option page to send test messages 
		 */
		function test_messages() {
								
			// Send a test mail if necessary
			if (isset($_POST['wpms_action']) && $_POST['wpms_action'] == __('Queue Test Email', 'pbci-mail') && isset($_POST['to'])) {
			
				// Set up the mail variables
				$to = $_POST['to'];
				$subject = 'PBCI Mail Queue: ' . __('Test mail to ', 'pbci-mail') . $to;
				$message = __('PBCI Mail Queue test message sent by the administrator.', 'pbci-mail');
				$result = wp_mail($to,$subject,$message);
				// Output the response
				?>
				<div id="message" class="updated fade"><p><strong><?php _e('Test Message Sent (Queued)', 'pbci-mail'); ?></strong></p>
				<p><?php _e('The result was:', 'pbci-mail'); echo($result);?></p>
				</div>
				<?php						
			}
									
			?>						
			<div class="wrap">
				<h2><?php _e('PBCI Mail Queue Testing', 'pbci-mail'); ?></h2>
								
				<h3>
					<?php _e('Queue a Test Email', 'pbci-mail'); ?>
				</h3>
						
				<form method="POST">
				
					<?php
					if ( function_exists('wp_nonce_field') )
						wp_nonce_field('pbci-mail-test');
					?>
									
					<table class="optiontable form-table">
						<tr valign="top">
							<th scope="row">
								<label for="to">
									<?php _e('To:', 'pbci-mail'); ?>
								</label>
							</th>
							<td>
								<input name="to" type="text" id="to" value="" size="40" class="code" /><br>
								<span class="description">
									<?php _e('Type an email address here and then click Send Test to generate and then queue a test email.', 'pbci-mail'); ?>
								</span>
							</td>
						</tr>
					</table>
					<p class="submit">
						<input type="submit" name="wpms_action" id="wpms_action" class="button-primary" value="<?php _e('Queue Test Email', 'pbci-mail'); ?>" />
					</p>
				</form>
			</div>
			
			
			
			<?php
			please_donate();
		}
		
		/**
		 * 
		 * Display an options page listing messages that remain in the mail queue
		 */
		function mailqueue() {
		}

		function show_error($msg,$action,$info) {
			
			echo '<div id="message" class="error">';
			//echo '<span style="color:red;">';
			if ( isset($msg) && ($msg != '') )
				echo '<h3>'.$msg.'</h3>';
			if ( isset($action)  && ($action != '') )
				echo '<b>'.$action.'</b><br>';
			if ( isset($info)  && ($info != '') )
				echo '<i>'.$info.'</i><hr><br><br>';
			//echo '</span>';
			echo '</div>';
		}
		
		/**
		 * check_non_deliveries():
		 * Access the mailbox that is collecting NDrs and update the message queue and statistics to 
		 * reflect any failed messages
		 */
		function check_non_deliveries() {
			global $wpdb;
			
			if ( $this->pbci_queue_options['pbci_mail_ndr_checking'] != true ) {
				return;
			}
			
			
			$headers = array (
					'X-PBCI-MID' => '',
					'X-PBCI-Q' => '',
					'Diagnostic-Code' => '',
					'Original-Recipient' => '',
					'Status' => '',
					'Content-Type' => '',
					'Subject' => '',
					'Final-Recipient' => ''
				);

			$pop3 = new POP3();

			$server = $this->pbci_queue_options['pbci_mail_ndr_mbox_server'];
			$port = $this->pbci_queue_options['pbci_mail_ndr_mbox_port'];
			$user = $this->pbci_queue_options['pbci_mail_ndr_mbox_user'];
			$password = $this->pbci_queue_options['pbci_mail_ndr_mbox_password'];
			
			
			$result = $pop3->connect( $server, $port );
			if ( !$result ) {
				$this->show_error(
							__('Checking For Non Deliveries: Error connecting to POP3 Server.'),
							__('Check Non-Delivery Report POP3 server and Non-Delivery Report POP3 port in Mail Queues Settings Page.'),
							esc_html( $pop3->ERROR )
							); 
				echo esc_html( $pop3->ERROR );
				return;
			}

			$result = $pop3->user( $user );
			if ( !$result ) {
				$this->show_error(
							__('Checking For Non Deliveries: Error when signing in with user name. '),
							__('Check server and port in Mail Queues Settings Page.'),
							esc_html( $pop3->ERROR )
							); 
				return;
			}

			$messageCount = $pop3->pass( $password );
			if( false === $messageCount ) {
				$this->show_error(
							__('Checking For Non Deliveries: Error when signing in with password'),
							__('Check server and port in Mail Queues Settings Page.'),
							esc_html( $pop3->ERROR )
							); 
				return;
			}

			if( 0 === $messageCount ) {
				$pop3->quit();
				return;
			}

			for ( $messageIndex = 1; $messageIndex <= $messageCount; $messageIndex++ ) {
				// clear out current headers
				foreach ($headers as $h => $value) { $headers[$h] = ''; }

				$message = $pop3->get($messageIndex);

				$lineCount = count($message);
				$isSuccessReport = false;
				$isNDR = false;

				for ( $lineIndex = 1; $lineIndex < $lineCount; $lineIndex++ ) {

					$line = $message[$lineIndex];
					if ( $lineIndex+1 < ($lineCount - 1) ) {
						$nextLine = $message[$lineIndex + 1];
					} else {
						unset($nextLine);
					}

					foreach ($headers as $h => $value) {
						$pattern = '/^'.$h.': /i';
						if ( preg_match($pattern, $line) ) {
							$value = trim($line);
							$keylen = strlen($h)+2; // length of the header key plus the space and colon
							$value = substr($value, $keylen, strlen($value) - $keylen );
							if ( isset($nextLine) ) {
								while ( isset($nextLine) && ($nextLine[0] == ' ') ) {
									$value = $value.' '.trim($nextLine);
									$lineIndex++;

									if ( $lineIndex+1 < $lineCount-1 ) {
										$nextLine = $message[$lineIndex+1];
									} else {
										unset($nextLine);
									}
								}
							}
							$headers[$h] = $value;

							if ( strcasecmp($h,'Content-Type') == 0 ) {
								if ( preg_match('/deliverystatus/',$value ) ) {
									$isNDR = true;
								}
							}

							if ( strcasecmp($h,'Subject') == 0 ) {
								if ( preg_match('/Delivery Status Notification/',$value )==1 ) {
									$isNDR = true;
								}
							}

							if ( strcasecmp($h,'Diagnostic-Code') == 0 ) {
								$isNDR = true;
							}

							if ( strcasecmp($h,'Status') == 0) {
								if ( $value[0] == '4' ) {
									$isNDR = true;
								} elseif ( $value[0] == '2' ) {
									$isSuccessReport = true;
								}
							}
						}
					}
				}

				/*
				 * Do we have an NDR with a matching message id and queue id?  if so
				 * we find the message id in the database, increment the error count.
				 * If the message error count is under the maximum allowed we mark it as unset.
				 * If the message count is over the maximum allowed we should notify someone.
				 *
				 */
				if ( !$isSuccessReport ) {
					if ( (strlen($headers['X-PBCI-MID']) > 0) && (strlen($headers['X-PBCI-Q']) > 0) && $isNDR ) {

						/*
						 * Any queues that send an NDR are going to get disabled, this stops the NDRs and redirects messages to other queues.
						 * The message needs to be reqeued or marked as unsent
						 */
						if ( $this->resend_message($headers['X-PBCI-MID']) == true ) {
							$this->disable_queue($headers['X-PBCI-Q'],true);
						} 						
							
						if( !$pop3->delete( $messageIndex ) ) {
							
							$this->show_error(
								__('Checking For Non Deliveries: problem deleting message from non-delivery mailbox.'),
								__('Check server,port, user name and password settings in Mail Queues Settings Page. Try manually deleting a message from mailxbox.'),
								esc_html( $pop3->ERROR )
							);
													
							$pop3->reset();
						} else {
							
							$this->show_error(
							__('Checking For Non Deliveries: Processed and then Removed message from non-delivery mailbox.'),
							__('If problem sending persists check recipient address or mail queue limits.'),
							''
							);
											
						}
					}
				}
			}
			
			$pop3->quit();
			
			return;
		}

		/**
		*
		* Make sure that if there are messages in the queue there is a cron job schueduled to dequeue and
		* then send
		*/
		function verify_queue_cronjob() {
			global $wpdb;
			$sql = 'SELECT count(*) as sent  FROM `'.$this->queue_table.'` WHERE sent=0 AND attempts<'.$this->pbci_queue_options['pbci_mail_max_send_attempts'];
				
			$unsentcount = $wpdb->get_var( $wpdb->prepare( $sql ) );
			$timestamp = wp_next_scheduled( $this->cron_action );
		
			if ( ($unsentcount > 0) && ($timestamp == false) ) {
				$timestamp = time() + $this->pbci_queue_options['pbci_mail_dequeue_wait'];
				wp_schedule_single_event( $timestamp, $this->cron_action );
			}
		}				
		
		function __construct() {
			global $wpdb;
			
			/** Brute force initialization of a few variables, may move to a 
			 ** configuration option in a future release 
			 */
			$this->testmode = false;
			$this->cron_action = 'do_mail_dequeue';
			$this->queue_table = $wpdb->prefix.'pbci_mail_queue';
			$this->control_table = $wpdb->prefix.'pbci_mail_control';
						
			$this->get_admin_options();

			/** the actions that we use in our plugin need to be defines
			** 
			*/					
			$result = add_action( $this->cron_action, array(&$this,'pbci_mail_dequeue'));
			$result = add_action( 'init',  array(&$this, 'mailqueue'));
			$result = add_action( 'admin_init', array(&$this, 'register_mysettings') );
			$result = add_action( 'admin_menu',array(&$this,'mail_queue_admin_pages'));
						
			//$this->verify_queue_cronjob();

			return true;
		}
		
		/*
		* Variables for our class
		*/
		var $admin_options_name = 'PBCIMailOptions';
		var $pbci_queue_options = array (
			'pbci_mail_disable_dequeue' => false,
			'pbci_mail_set_reply_to' => false,
			'pbci_mail_reply_to_email' => 'postmaster@localhost',
			'pbci_mail_reply_to_name' => 'Web Site Postmaster',
			'pbci_mail_log_all_mail' => 'true',
			'pbci_mail_disable_dequeue' => false,
			'pbci_mail_from_name' => 'My Wordpress Web Site',
			'pbci_mail_max_send_attempts' => 3,		// maximum number of messages per send burst
			'pbci_mail_max_batch' => 1,    			// maximum number of messages per send burst
			'pbci_mail_dequeue_wait' => 60, 		// wait 60 seconds between send bursts
			'pbci_mail_max_allowed_queue_errors'=>0,		// after this number of errors disable the queue for a period
			'pbci_mail_queue_disable_minutes'=>60,  	// how long to disable a queue for after hitting the error count, in seconds
			'pbci_mail_debug_mode_address'=>'', // in test mode send messages to this address
			'pbci_mail_debug_mode_server' => 'localhost.local',
			'pbci_mail_ndr_checking' => true,
			'pbci_mail_ndr_mbox_server' => 'ssl://pop.gmail.com',
			'pbci_mail_ndr_mbox_port' => 995,
			'pbci_mail_ndr_mbox_user' => 'username',
			'pbci_mail_ndr_mbox_password' => 'password',
			'pbci_mail_version' => '1.0'
			);
		
		var $disable_dequeue;
		var $testmode;
		var $pbci_smtp_queue;
		var $queue_table;
		var $control_table;
		var $cron_action;
		var $last_send_smtp_debug;	
		
		
		/**
		 * 
		 * retrieve and sanitize the value of a specified plugin option
		 * @param string $key - the name of the option to retrieve and sanitize
		 * @return Ambigous <string, multitype:boolean string number 
		 */
		function optiontext($key) {
			$val = '';
			if ( isset($this->pbci_queue_options[$key]) ) {
				$val = $this->pbci_queue_options[$key];
			}
			return $val;
		}
		

		function recipient_count( $to, $subject, $message, $headers = '') {
		
			
			$recipient_count = 0;
			
			/*
			 * Go through the headers and look for values to use withyin the message envelope and
			* message body (Originally from the phpmailer puggable_wp_mail function - look thier for credits)
			*/
			if ( empty( $headers ) ) {
				$headers = array();
			} else {
				if ( !is_array( $headers ) ) {
					// Explode the headers out, so this function can take both
					// string headers and an array of headers.
					$headers = str_replace( "\r\n", "\n", $headers );
					$tempheaders = explode( "\n",  $headers );
				} else {
					$tempheaders = $headers;
				}
				$headers = array();
		
				// If it's actually got contents
				if ( !empty( $tempheaders ) ) {
					// Iterate through the raw headers
					foreach ( (array) $tempheaders as $header ) {
						if ( strpos($header, ':') === false ) {
							if ( false !== stripos( $header, 'boundary=' ) ) {
								$parts = preg_split('/boundary=/i', trim( $header ) );
								$boundary = trim( str_replace( array( '\'', '"' ), '', $parts[1] ) );
							}
							continue;
						}
						// Explode them out
						list( $name, $content ) = explode( ':', trim( $header ), 2 );
		
						// Cleanup crew
						$name    = trim( $name    );
						$content = trim( $content );
		
						switch ( strtolower( $name ) ) {
							// Mainly for legacy -- process a From: header if it's there
							case 'cc':
								$cc = array_merge( (array) $cc, explode( ',', $content ) );
								break;
							case 'bcc':
								$bcc = array_merge( (array) $bcc, explode( ',', $content ) );
								break;
								break;
						}
					}
				}
			}
		
			/*
			 * Done with headers at this point we should know if we have $bcc, $cc,
			* $from_email and $from_name
			*
			*/
		
		
			/*
			 * The message has to be sent to all of the people in the $to array
			*/
			if (!empty($to) ) {
				if ( !is_array( $to ) )
				$to = explode( ',', $to );
			}
		
			$recipient_count = count($to);
		
			// Add any CC and BCC recipients
			if ( !empty( $cc ) ) {
				$recipient_count += count($cc);
			}
		
			if ( !empty( $bcc ) ) {
				$recipient_count += count($bcc);
			}
		
			return $recipient_count;
		}
		
		/**
		* This function outputs the plugin options page.
		*/		
		// Define the function
		function options_page() {
			
			?>
		<div class="wrap">
		<h2><?php _e('PBCI Mail Queue Options', 'pbci-mail'); ?></h2>
		<form method="post" action="options.php">
		<?php
		if ( function_exists('wp_nonce_field') )
			wp_nonce_field('pbci-mail-options');
		?>
				
		<?php 
		settings_fields( $this->admin_options_name ); 
		//do_settings_fields( $this->admin_options_name );
		?>
		
		<table class="optiontable form-table">

			<!--  'pbci_mail_disable_dequeue' -->
			<tr valign="top">
				<th scope="row"><?php _e('Dequeue', 'pbci-mail'); ?> </th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e('Queueing', 'pbci-mail'); ?></span>
						</legend>
						<label for="pbci_mail_disable_dequeue">
								<input name="pbci_mail_disable_dequeue" type="checkbox" id="pbci_mail_disable_dequeue" value="true" <?php checked('true', get_option('pbci_mail_disable_dequeue'));?>/>
								<?php  _e('Disable mail dequeing');?> <br>
								<span class="description">
									<?php _e('Check this box to stop email messages from being taken off the queue and sent.  Messages will continue to be queued until this option is unchecked. '); ?>
								</span>
						</label>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th colspan="2">
					<h3><?php _e('Message Header Options', 'pbci-mail'); ?></h3>
				</th>
			</tr>


			<!-- 'pbci_mail_from_name' --> 			
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_from_name"><?php _e('From Name', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_from_name" type="text" id="pbci_mail_from_name" value="<?php echo get_option('pbci_mail_from_name'); ?>" size="40" class="regular-text" /><br>
					<span class="description">
						<?php _e('Emails will appear like `John Smith via <b>My Web Site</b>`. You can specify the <b>My Web Site</b> part here.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		
				
			<!-- 'pbci_mail_set_reply_to'  -->
			<tr valign="top">
				<th scope="row"><?php _e('Reply To', 'pbci-mail'); ?> </th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e('Queueing', 'pbci-mail'); ?></span>
						</legend>
						<label for="pbci_mail_set_reply_to">
								<input name="pbci_mail_set_reply_to" type="checkbox" id="pbci_mail_set_reply_to" value="true" <?php checked('true', get_option('pbci_mail_set_reply_to'));?>/>
								<?php  _e('Add `Reply To`');?> <br>
								<span class="description">
									<?php _e('Check this box to add an additional <b>Reply To</b> to each sent message.  There is a possibility that adding the reply to will increase a messages SPAM score. It might be better to setup the sending mailbox to automatically forward the reply message to the desired address. '); ?>
								</span>
						</label>
					</fieldset>
				</td>
			</tr>
				

			<!-- 'pbci_reply_to_email' -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_reply_to_email"><?php _e('Reply To Email Address', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_reply_to_email" type="text" id="pbci_mail_reply_to_email" value="<?php echo get_option('pbci_mail_reply_to_email'); ?>" size="40" class="regular-text" /><br>
					<span class="description">
						<?php _e('If <b>Reply To</b> is used, this will be the email address.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		


			<!-- 'pbci_reply_to_name' -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_reply_to_name"><?php _e('Reply To Name', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_reply_to_name" type="text" id="pbci_mail_reply_to_name" value="<?php echo get_option('pbci_mail_reply_to_name'); ?>" size="40" class="regular-text" /><br>
					<span class="description">
						<?php _e('If <b>Reply To</b> is used, this will be the name used.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		

			<tr valign="top">
				<th colspan="2">
					<h3><?php _e('Queuing and Dequeuing Options', 'pbci-mail'); ?></h3>
				</th>
			</tr>



			<!-- 'pbci_mail_max_send_attempts',		// maximum number of messages per send burst -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_max_send_attempts"><?php _e('Max Message Send Attempts', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_max_send_attempts" type="text" id="pbci_mail_max_send_attempts" value="<?php echo get_option('pbci_mail_max_send_attempts'); ?>" size="6" class="small-text" /> <?php _e('tries', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('   this number of errors attempting to send a message trying to send this message will stop.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		

			<!-- 'pbci_mail_max_batch'   			// maximum number of messages per send burst  -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_max_batch"><?php _e('Messages Per Batch', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_max_batch" type="text"id="pbci_mail_max_batch" value="<?php echo get_option('pbci_mail_max_batch'); ?>" size="6" class="small-text" /> <?php _e('messages', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('The number of messages will be sent per session per queue.  It is recomended that the number of messages per session be set to 1 so that an email server has an opportunity to verify send quotas between messages.  This should significantly reduce the number of local and downstream NDRs.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		

			<!-- 'pbci_mail_dequeue_wait' 		// wait 60 seconds between send bursts -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_dequeue_wait"><?php _e('Dequeue Interval', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_dequeue_wait" type="text" id="pbci_mail_dequeue_wait" value="<?php echo get_option('pbci_mail_dequeue_wait'); ?>" size="6" class="small-text" /> <?php _e('seconds', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('Emails are taken from the queue using the interval specified here. 60 Seconds is the default value.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		

			<!-- 'pbci_max_allowed_queue_errors'		// after this number of errors disable the queue for a period -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_max_allowed_queue_errors"><?php _e('Max Errors Before Queue Disable', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_max_allowed_queue_errors" type="text" id="pbci_mail_max_allowed_queue_errors" value="<?php echo get_option('pbci_mail_max_allowed_queue_errors'); ?>" size="6" class="small-text" /> <?php _e('errors', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('After this number of errors while attempting to send the queue will be disabled for a period of time. If set to 0 a queue will be temporarily disabled after the first SMTP send error or first NDR. ', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		

			<!-- 'pbci_mail_queue_disable_minutes' 	// how long to disable a queue for after hitting the error count, in seconds -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_queue_disable_minutes"><?php _e('Disable Time', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_queue_disable_minutes" type="text" id="pbci_mail_queue_disable_minutes" value="<?php echo get_option('pbci_mail_queue_disable_minutes'); ?>" size="6" class="small-text" /> <?php _e('minutes', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('Queues that are automatically disabled due to errros stay off for this number of minutes, then they are turned on automatically. One hour (60) is recommended.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		


			<tr valign="top">
				<th colspan="2">
					<h3><?php _e('Non-Delivery Report (NDR) Processing', 'pbci-mail'); ?></h3>
				</th>
			</tr>

			<!--  'pbci_mail_ndr_checking' -->
			<tr valign="top">
				<th scope="row"><?php _e('Check for Non-Delivery Reports', 'pbci-mail'); ?> </th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e('Queueing', 'pbci-mail'); ?></span>
						</legend>
						<label for="pbci_mail_ndr_checking">
								<input name="pbci_mail_ndr_checking" type="checkbox" id="pbci_mail_ndr_checking" value="true" <?php checked('true', get_option('pbci_mail_ndr_checking'));?>/>
								<?php  _e('Check to enable NDR processing, unchecked to disable');?> <br>
								<span class="description">
									<?php _e('Enable checking for Non-Delivery Reports (NDRs) in the specified NDR mailbox.  Configure each of the queue mailboxes to forward any received mail to the NDR mailbox. '); ?>
								</span>
						</label>
					</fieldset>
				</td>
			</tr>



			<!-- 'pbci_mail_ndr_mbox_server'  	// how long to disable a queue for after hitting the error count, in seconds -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_ndr_mbox_server"><?php _e('Non-Delivery Report POP3 server', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_ndr_mbox_server" type="text" id="pbci_mail_ndr_mbox_server" value="<?php echo get_option('pbci_mail_ndr_mbox_server'); ?>" size="6" class="regular-text" /> <?php _e('like `ssl://pop.gmail.com`', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('The server name that will allow connection to the POP3 mailbox from where NDR messages will be collected.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		


			<!-- 'pbci_mail_ndr_mbox_user'  -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_ndr_mbox_user"><?php _e('Non-Delivery Report POP3 user name', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_ndr_mbox_user" type="text" id="pbci_mail_ndr_mbox_user" value="<?php echo get_option('pbci_mail_ndr_mbox_user'); ?>" size="6" class="regular-text" /> <?php _e('like `joe.smith@gmail.com`', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('The user name that will allow connection to the POP3 mailbox from where NDR messages will be collected.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		


			<!-- 'pbci_mail_ndr_mbox_password' -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_ndr_mbox_password"><?php _e('Non-Delivery Report POP3 password', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_ndr_mbox_password" type="text" id="pbci_mail_ndr_mbox_password" value="<?php echo get_option('pbci_mail_ndr_mbox_password'); ?>" size="6" class="regular-text" /> <?php _e('', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('The password that will allow connection to the POP3 mailbox from where NDR messages will be collected.', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		


			<!-- 'pbci_mail_ndr_mbox_port'-->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_ndr_mbox_port"><?php _e('Non-Delivery Report POP3 port', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_ndr_mbox_port" type="text" id="pbci_mail_ndr_mbox_port" value="<?php echo get_option('pbci_mail_ndr_mbox_port'); ?>" size="6" class="small-text" /> <?php _e('port 110 or 995 are common', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('The port used to connect to the POP3 mailbox from where non-delivery messages will be collected', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		



			<tr valign="top">
				<th colspan="2">
					<h3><?php _e('Testing and Debugging Options', 'pbci-mail'); ?></h3>
				</th>
			</tr>


			<!-- 'pbci_mail_log_all_mail'-->
			<tr valign="top">
				<th scope="row"><?php _e('Message Logging', 'pbci-mail'); ?> </th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e('Log All Mail', 'pbci-mail'); ?></span>
						</legend>
						<label for="pbci_mail_log_all_mail">
								<input name="pbci_mail_log_all_mail" type="checkbox" id="pbci_mail_log_all_mail" value="true" <?php checked('true', get_option('pbci_mail_log_all_mail'));?>/>
								<?php  _e('Log All Mail');?> <br>
								<span class="description">
									<?php _e('Logs all messages, instead of only the failures.'); ?>
								</span>
						</label>
					</fieldset>
				</td>
			</tr>

			<!-- 'pbci_mail_debug_mode_server'-->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_debug_mode_server"><?php _e('Debug mode server name', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_debug_mode_server" type="text"  id="pbci_mail_debug_mode_server" value="<?php echo get_option('pbci_mail_debug_mode_server'); ?>" size="40" class="regular-text" /> <?php _e('like `localhost`', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('If the local server has this name the mail queue plugin automatically switches to test mode.  Test mode raises the logging level, and also overrides to `to` field on each message to the test mode address so that the single recipeint messages do not go to real users', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		


			<!-- 'pbci_mail_debug_mode_address' // in test mode send messages to this address  -->
			<tr valign="top">
				<th scope="row">
					<label for="pbci_mail_debug_mode_address"><?php _e('Debug mode address', 'pbci-mail'); ?></label>
				</th>
				<td>
					<input name="pbci_mail_debug_mode_address" type="text" id="pbci_mail_debug_mode_address" value="<?php echo get_option('pbci_mail_debug_mode_address'); ?>" size="40" class="regular-text" /> <?php _e('like `somebody@localhost.com`', 'pbci-mail')?><br>
					<span class="description">
						<?php _e('When in test mode emails will be sent to this address.  Caution: watch out for messages with bcc or cc fields!', 'pbci-mail'); ?>
					</span>
				</td>
			</tr>		


			<!-- 'pbci_mail_debug_mode_address' // in test mode send messages to this address  -->
			<?php if ( isset($_SERVER['SERVER_NAME'] ) ) $servername = $_SERVER['SERVER_NAME']; else $servername = "(unknown)"; ?>
			
			<tr valign="top">
				<th scope="row">
					<?php _e('Test or Live Server Mode', 'pbci-mail'); ?>
				</th>
				<td>
					<span class="description">
						<?php _e('Current server name is: <b>', 'pbci-mail').print($servername); echo '</b>     ';

						if ( $this->testmode )
							_e('Running in test mode', 'pbci-mail'); 
						else
							_e('Running in live mode', 'pbci-mail');
						?>						
					</span>
				</td>
			</tr>		
		</table>		
				
		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
		<input type="hidden" name="action" value="update" />
		
		<!-- <input type="hidden" name="option_page" value="email"> -->
		</form>
		
		</div>
			<?php
			please_donate();
		} // End of pbci-mail_options_page() function definition

				
		/**
		*
		* Displays the manage page for the plugin.
		*/
		function pbci_mail_queue_setup() {
			global $wpdb;
		
			if ( $this->edit_queue() )
				return;
			
			if ( $this->delete_queue() )
				;
			
			?><div class="wrap"><?php
			        
	        echo '<h2>'; _e( 'Message Queue Setup', 'pbci-mail' ); echo '</h2>';
		
	        // get a mail queues from the status view
			$sql = 'SELECT * FROM `'.$this->control_table.'` ORDER BY `queue_id`';
			$pbci_smtp_queues = $wpdb->get_results( $sql, ARRAY_A );
	        
	        ?>
	        <h2><?php _e( 'SMTP Mail Queues', 'pbci-mail' ); ?></h2>
	        <p></p>
	        <table class="widefat">
	        <thead>
	            <tr>
	                <th  align="center" colspan="2"><?php _e('Actions', 'pbci-mail'); ?></th>
	                <!-- <th><?php _e('ID', 'pbci-mail'); ?></th>  -->
	                <th><?php _e('Server', 'pbci-mail'); ?></th>
	                <th><?php _e('login', 'pbci-mail'); ?></th>
	                <th><?php _e('password', 'pbci-mail'); ?></th>
	                <th><?php _e('ssl', 'pbci-mail'); ?></th>
	                <th><?php _e('port', 'pbci-mail'); ?></th>
	                <th><?php _e("max per hour", 'pbci-mail'); ?></th>
	                <th><?php _e('max per day', 'pbci-mail'); ?></th>
	            </tr>
	        </thead>
	        <tbody>
	        
	        <?php   
			        if( empty($pbci_smtp_queues) ) {
			            echo '<tr colspan="9"><td>';_e('You currently have no mail queues. ', 'pbci-mail');echo '</td></tr>';
			        } else {
			            $class = '';
		
			            foreach( $pbci_smtp_queues as $queue ) {

			                echo '<tr id=\"q-'.$queue['queue_id'].'\">';
			                
			                $editlink = "admin.php?page=pbci_mail_queue_setup&amp;action=edit-queue&amp;queue_id=".$queue['queue_id'];
			                $editlink = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($editlink, 'pbci-mail-edit') : $editlink;
							echo "<td><a class='view' href='".$editlink."'>Edit</a></td>";

							$deletelink = "admin.php?page=pbci_mail_queue_setup&amp;action=delete-queue&amp;queue_id=".$queue['queue_id'];
							$deletelink = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($deletelink, 'pbci-mail-edit') : $deletelink;															
							echo "<td><a class='view' href='".$deletelink."'>Delete</a></td>";																		
			                
							echo '<td>'.$queue['server'].'</td>';
			                echo '<td>'.$queue['login'].'</td>';
			                echo '<td>'.$queue['password'].'</td>'; 
			                echo '<td>'.$queue['ssl'].'</td>';
			                echo '<td>'.$queue['port'].'</td>';
			                echo '<td>'.$queue['max_per_hour'].'</td>';
			                echo '<td>'.$queue['max_per_day'].'</td>';
			                //$nonce_url = wp_nonce_url("admin.php?page=pbci_mail_queue_setup&amp;action=delete-queue&amp;id=".$queue['queue_id'], "delete-cron_".$queue['queue_id']);
			                //echo "<td><a class='delete' href='".$nonce_url."'>Delete</a></td>";
			                 
			                echo '</tr>';
			            }			            			             
			        }			        
			?>			
				</tbody></table>
			<?php
			
				echo '<p>';
					_e('You can define a new  Mail Queue', 'pbci-mail');echo " ";
					$newlink = "admin.php?page=pbci_mail_queue_setup&amp;action=edit-queue&amp;queue_id=-1";
					$newlink = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($newlink, 'pbci-mail-edit') : $deletelink;
					echo "<td><a class='view' href='".$newlink."'>New Queue</a></td>";
			?>
				</div>
			<?php
			please_donate();
		}
			        

		/**
		*
		* Display an option page to send test messages
		*/
		function delete_queue() {
			global $wpdb;
								
			if ( isset($_REQUEST['queue_id']) )
				$queue_id = $_REQUEST['queue_id'];
		
			if ( isset($_REQUEST['action']) )
				$action = $_REQUEST['action'];
			
			if ( !(isset($queue_id) && isset($action) ) )
				return false;

			if ( $action === 'delete-queue' ) {
				check_admin_referer('pbci-mail-edit');				
				
				$sql = "DELETE FROM ".$this->control_table." WHERE `queue_id` = ".$queue_id;
				$result = $wpdb->query( $sql );
				
				if ( $result == 1 ) {
					
					?>
							<div id="message" class="updated fade">
								<p>
									<strong><?php _e('Queue Deleted', 'pbci-mail'); ?></strong>
								</p>
							</div>
					<?php 												
					;
				}
			}				
		}
		
		/**
		*
		* Display an option page to send test messages
		*/
		function edit_queue() {
			global $wpdb;
			
			if ( isset($_REQUEST['queue_id']) )
				$queue_id = $_REQUEST['queue_id'];

			if ( isset($_REQUEST['action']) )
				$action = $_REQUEST['action'];

			if ( !(isset($queue_id) && isset($action) ) )
				return false;

			if ( $action !== 'edit-queue')
				return false;			

			check_admin_referer('pbci-mail-edit');
			
			$save_button_clicked = isset( $_REQUEST['edit-queue-save']);
			$cancel_button_clicked = isset( $_REQUEST['edit-queue-cancel']);
				
				
			if ( $save_button_clicked ) {
				// Set up the mail variables
				// Output the response

				$server = $_POST['server'];
				$login_name = $_POST['login_name'];
				$password = $_POST['password'];
				$port = $_POST['port'];
				$max_per_hour = $_POST['max_per_hour'];
				$max_per_day = $_POST['max_per_day'];
				$security = $_POST['security'];
				
				$data = array(
								'server' => $server,
								'login' => $login_name,
								'port' => $port,
								'password' => $password,
								'ssl' => $security,
								'max_per_hour' => $max_per_hour,
								'max_per_day' => $max_per_day 
						);
				
				if ( $queue_id > 0 ) {
					$rows_updated = $wpdb->update( ''.$this->control_table.'',$data, array( 'queue_id' => $queue_id ) );
				} else {
					$rows_updated = $wpdb->insert( ''.$this->control_table.'',$data );
				}
				

				if ( $rows_updated == 0 ) {
					?>
							<div id="message" class="updated fade">
								<p>
									<strong><?php _e('No Changes to the Mail Queue.', 'pbci-mail'); ?></strong>
								</p>
							</div>
					<?php 												
					return false;// that's what supposed to happen, false tells the caller that this routine handled the process
				}				
								
				
				if ( $rows_updated == 1 ) {
					?>
						<div id="message" class="updated fade">
							<p>
								<strong><?php _e('Mail Queue Modifications Successfully Saved.', 'pbci-mail'); ?></strong>
							</p>
						</div>
					<?php 												
					return false;// that's what supposed to happen, false tells the caller that this routine handled the process
				}

				if ( $rows_updated == false ) {
					$wpdb->print_error();
					//that's a problem, database issue? what to do?
				}
			}						
			
			if ( $cancel_button_clicked ) {
				// Set up the mail variables
				// Output the response
				?>
					<div id="message" class="updated fade">
						<p>
						<strong><?php _e('Mail Queue Setup Cancelled. No Changes Made.', 'pbci-mail'); ?></strong>
						</p>
					</div>
									
				<?php
				return false;
			}
						
			if ( $queue_id > 0 ) {
				$sql = 'SELECT * FROM `'.$this->control_table.'` WHERE queue_id='.$queue_id;
				$queue = $wpdb->get_row($sql, ARRAY_A );
				if ( $queue == false ) {
					?><strong><?php _e('Could not get queue information from database to modify.', 'pbci-mail'); ?></strong><?php 
					return false;
				}
				
				$server = $queue['server'];
				$login_name = $queue['login'];
				$password = $queue['password'];
				$port = $queue['port'];
				$max_per_hour = $queue['max_per_hour'];
				$max_per_day = $queue['max_per_day'];
				$security = $queue['ssl'];
				
			} else {
				// default values for a mail queue
				$server = 'localhost';
				$login_name = '';
				$password = '';
				$port = '25';
				$max_per_hour = '10';
				$max_per_day = '100';
				$security = 'none';				
			}
			
				?>						
					<div class="wrap">
						<h2><?php if ( $queue_id > 0 ) { _e('Modify Mail Queue', 'pbci-mail'); } else {_e('New Mail Queue', 'pbci-mail');} ?></h2>
										
						<form method="POST">
						
							<?php
							if ( function_exists('wp_nonce_field') )
								wp_nonce_field('pbci-mail-edit');
							?>
						
							<table class="optiontable form-table">
							
								<tr valign="top">
									<th scope="row">
										<label for="server">
											<?php _e('server:', 'pbci-mail'); ?>
										</label>
									</th>
									<td>
										<input name="server" type="text" id="server" value="<?php echo $server; ?>" size="40" class="regular-text" /><br>
										<span class="description">
											<?php _e('Enter the SMTP Server Name', 'pbci-mail'); ?>
										</span>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="login">
											<?php _e('login:', 'pbci-mail'); ?>
										</label>
									</th>
									<td>
										<input name="login_name" type="text" id="login_name" value="<?php echo $login_name;?>" size="40" class="regular-text"/><br>
										<span class="description">
											<?php _e('Enter the SMTP Server Login Name', 'pbci-mail'); ?>
										</span>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="password">
											<?php _e('password:', 'pbci-mail'); ?>
										</label>
									</th>
									<td>
										<input name="password" type="text" id="password" value="<?php echo $password;?>" size="40" class="regular-text" /><br>
										<span class="description">
											<?php _e('Enter the SMTP Password', 'pbci-mail'); ?>
										</span>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="port">
											<?php _e('port:', 'pbci-mail'); ?>
										</label>
									</th>
									<td>
										<input name="port" type="text" id="port" value="<?php echo $port;?>" size="40" class="small-text" /><br>
										<span class="description">
											<?php _e('Enter the SMTP Server Name', 'pbci-mail'); ?>
										</span>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="max_per_hour">
											<?php _e('maximum recipients per hour:', 'pbci-mail'); ?>
										</label>
									</th>
									<td>
										<input name="max_per_hour" type="text" id="max_per_hour" value="<?php echo $max_per_hour;?>" size="40" class="small-text" /><br>
										<span class="description">
											<?php _e('Enter the SMTP Server Maximum Recipients per Hour', 'pbci-mail'); ?>
										</span>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="max_per_day">
											<?php _e('maximum recipients per day:', 'pbci-mail'); ?>
										</label>
									</th>
									<td>
										<input name="max_per_day" type="text" id="max_per_day" value="<?php echo $max_per_day;?>" size="40" class="small-text" /><br>
										<span class="description">
											<?php _e('Enter the SMTP Server Maximum Recipients per Day', 'pbci-mail'); ?>
										</span>
									</td>
								</tr>

								<tr valign="top">
									<th scope="row">
										<?php _e('Connection Security', 'pbci-mail'); ?> 
									</th>
									
									<td>
										<fieldset>
											<legend class="screen-reader-text">
												<span><?php _e('SSL', 'pbci-mail'); ?></span>
											</legend>
											
												<input id="security_none" type="radio" name="security" value="none" <?php checked('none', $security); ?> />
												<label for="security_none">
													<?php _e('No Security.', 'pbci-mail'); ?>
												</label>
											
												<input id="security_ssl" type="radio" name="security" value="ssl" <?php checked('ssl', $security); ?> />
												<label for="security_ssl">
													<?php _e('Use SSL', 'pbci-mail'); ?>
												</label>
											
												<input id="security_tls" type="radio" name="security" value="tls" <?php checked('tls', $security); ?> />
												<label for="security_tls">
													<?php _e('Use TLS', 'pbci-mail'); ?>
												</label>
											
										</fieldset>
									</td>
								</tr>								
							</table>
							
							<p class="submit">
								<input type="submit" name="edit-queue-save" id="edit-queue-save" class="button-primary" value="Save" />
								<input type="submit" name="edit-queue-cancel" id="edit-queue-cancel" class="button-primary" value="Cancel" />
							</p>
						</form>
					</div>
				<?php

				please_donate();
				return true;
				}

				
				
			function pbci_mail_activate() {
				global $wpdb;
				
				$this->get_admin_options();
		
				$sql =
					"	CREATE TABLE IF NOT EXISTS `"
					.$this->control_table
					."` ( "
					."	  `enabled` tinyint(1) NOT NULL DEFAULT '1', "
					."	  `queue_id` int(11) NOT NULL AUTO_INCREMENT, "
					."	  `max_per_hour` int(11) NOT NULL, "
					."	  `max_per_day` int(11) NOT NULL, "
					."	  `server` varchar(255) NOT NULL, "
					."	  `port` varchar(255) NOT NULL, "
					."	  `login` varchar(255) DEFAULT NULL, "
					."	  `password` varchar(255) DEFAULT NULL, "
					."	  `ssl` enum('none','ssl','tls') NOT NULL DEFAULT 'none', "
					."	  `disabled_until` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', "
					."	  `errors` int(11) NOT NULL DEFAULT '0', "
					."	  `last_success` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', "
					."	  `last_failure` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', "
					."	  `sent_last_hour` int(11) NOT NULL DEFAULT '0', "
					."	  `sent_last_day` int(11) NOT NULL DEFAULT '0', "
					."	PRIMARY KEY (`queue_id`) "
					."	); ";

				$result = $wpdb->query( $sql );
								
				$sql =
					"CREATE TABLE IF NOT EXISTS `"
					.$this->queue_table
					."` ( "
					."  `queue_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, "
					."  `sent` tinyint(1) NOT NULL DEFAULT '0', "
					."  `sent_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', "
					."  `ack_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', "
					."  `id` int(11) NOT NULL AUTO_INCREMENT, "
					."  `msg_to` varchar(1024) NOT NULL, "
					."  `subject` varchar(1024) NOT NULL, "
					."  `msg_from` varchar(256) NOT NULL, "
					."  `message` text NOT NULL, "
					."  `headers` text NOT NULL, "
					."  `attachments` blob NOT NULL, "
					."  `recipients` int(11) NOT NULL DEFAULT '1', "
					."  `queue_id` int(11) NOT NULL, "
					."  `attempts` int(11) NOT NULL DEFAULT '0', "
					."  `smtp_msg_id` varchar(255) NOT NULL, "
					." PRIMARY KEY (`id`), "
					." KEY `queue_dt` (`queue_dt`,`sent_dt`,`ack_dt`), "
					." KEY `sent` (`sent`), "
					." KEY `sent_dt` (`sent_dt`), "
					." KEY `smtp_msg_id` (`smtp_msg_id`), "
					."  KEY `attempts` (`attempts`,`sent`) "
					." ); ";
				
				$result = $wpdb->query( $sql );
				
												
		}
		
		/**
		*
		* Setup the class, initialize variables, load configuration options and
		* make sure everything is ok before dong real work.
		*/
		function init() {
			register_activation_hook(__FILE__,array(&$this,'pbci_mail_activate'));
		}
		
		
						
	} //End Class PBCIMailQueue
} // End !class_exists('PBCIMailQueue')


/**
 * Instantiate our mailer queue class
 */
if ( !isset($mailqueue) ) {
	if ( class_exists('PBCIMailQueue')) {
		$mailqueue = new PBCIMailQueue();
		$mailqueue->init();
	}
}

function please_donate() {
// yes this a donate button that you can remove, but you will feel guilty in perpetuity if you delete this code before donating.  
// Remember that the softest pillow is a clear conscience :)
?>
<br><hr>
<table>
<tr>
<td>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="JQ8L23G6D3ANJ">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif"  name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form> 
</td>
<td>
Please consider making a small donation to support the continued development and support of this plugin.  Take a moment to consider how much time and money the plugin saves 
you. You don't have to pay for additional mail services, or check your non-delivery messsages.  Your site's users, customers, members and product sales are all happier 
because this plug-in is available.  Your emails are much less likely to be incorrectly flagged as SPAM.  Even your hosting provider likes you better.
<br>Thanks very much for considering a small contribution. 
</td>
</tr>   
</table>
<?php
 
}

/**
 * Overrides the wp_mail function
 */
if ( !function_exists( 'wp_mail' ) ) :
function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
	global $mailqueue;

	// Compact the input, apply the filters, and extract them back out
	extract( apply_filters( 'wp_mail', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) ) );

	$mailqueue->pbci_mail_queue( $to, $subject, $message, $headers, $attachments );
	return true;
}

endif;

?>