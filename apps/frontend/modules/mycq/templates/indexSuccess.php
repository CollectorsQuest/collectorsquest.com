<?php
cq_sidebar_title(
  "Welcome to Collectors' Quest, {$collector->getDisplayName()}!", null,
  array('class' => 'row-fluid sidebar-title spacer')
);
?>

<div class="row-fluid">
  <div class="span3">
    <p class="spacer-right">
      One of the best parts about collecting is sharing your love and treasures
      with others, so make sure to <a href="/mycq/profile">fill out your collector
      profile</a> so we can match you with people who share the same passion as you.
      Welcome to the community!
    </p>
  </div>
  <div class="span9 welcome-mycq">
    <div class="rectangle"></div>
    <div class="row-fluid content-box">
      <div class="span4 text-center">
        <i class="edit-profile"></i>
        <h3>Edit My Profile</h3>
        <p>
          Update your profile picture, edit your profile, email, or password
          <br>
          <a href="#">Show me how!</a>
        </p>
      </div>
      <div class="span4 text-center">
        <i class="edit_collections"></i>
        <h3>Edit My Collections</h3>
        <p>
          Upload new collections, add to existing ones or organize the collections
          that you have
          <br>
          <a href="#">Show me how!</a>
        </p>
      </div>
      <div class="span4 text-center">
        <i class="check_messages"></i>
        <h3>Check My Messages</h3>
        <p>
          Check your email from other members or write or send messages
          <br>
          <a href="#">Show me how!</a>
        </p>
      </div>
    </div>
  </div>
</div>


