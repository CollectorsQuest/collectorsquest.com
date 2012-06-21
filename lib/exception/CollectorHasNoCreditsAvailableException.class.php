<?php

/**
 * Exception to be used when an action requires a collector (seller) to have available
 * transaction credits, but he either has no PackageTransaction records, or all credits
 * for those records are used up
 */
class CollectorHasNoCreditsAvailableException extends Exception
{

}
