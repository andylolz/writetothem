<?php
/*
 * queue.php:
 * Interface to the fax/email queue.
 * 
 * Copyright (c) 2004 UK Citizens Online Democracy. All rights reserved.
 * Email: chris@mysociety.org; WWW: http://www.mysociety.org/
 *
 * $Id: queue.php,v 1.13 2004-11-17 12:29:59 chris Exp $
 * 
 */

include_once('../../phplib/rabx.php');
include_once('../../phplib/utility.php');

/* Error codes */
define('FYR_QUEUE_', 2001);        /* */

/* msg_get_error R
 * Return FALSE if R indicates success, or an error string otherwise. */
function msg_get_error($e) {
    if (!rabx_is_error($e))
        return FALSE;
    else
        return $e->text;
}


$fyr_queue_client = new RABX_Client(OPTION_FYR_QUEUE_URL);

/* msg_create
 * Return a string ID for a new outgoing fax/email message. */
function msg_create() {
    global $fyr_queue_client;
    debug("QUEUE", "Getting new message ID");
    $result = $fyr_queue_client->call('FYR.Queue.create', array());
    debug("QUEUE", "New ID is", $result);
    return $result;
}

/* msg_write ID SENDER RECIPIENT TEXT
 * Queue a new message for sending. ID is a message-ID obtained from
 * msg_create; SENDER is an associative array containing information about the
 * sender including elements: name, the sender's full name; email, their email
 * address; address, their full postal address; postcode, their postcode; and
 * optionally phone, their phone number. RECIPIENT is the DaDem ID number of
 * the recipient of the message; and TEXT is the text of the message, with only
 * "\n" characters for line breaks. All strings must be encoded in UTF-8.
 * Returns true on success or false on failure. */
function msg_write($id, $sender, $recipient_id, $text) {
    global $fyr_queue_client;
    /* XXX check contents of sender array */
    debug("QUEUE", "Writing new message id $id to $recipient_id", $sender);
    $result = $fyr_queue_client->call('FYR.Queue.write', array($id, $sender, $recipient_id, $text));
    debug("QUEUE", "Result:", $result);
    if (is_array($result)) // TODO replace this with better error handling code
        return false;
    return $result;
}

/* msg_record_questionnaire_answer TOKEN QUESTION ANSWER
 * Record the response to a questionnaire. TOKEN is the user-supplied token;
 * QUESTION should be 0, and ANSWER must be "YES" or "NO". */
function msg_record_questionnaire_answer($token, $qn, $answer) {
    global $fyr_queue_client;
    debug("QUEUE", "Recordng answer");
    $result = $fyr_queue_client->call('FYR.Queue.record_questionnaire_answer', array($token, $qn, $answer));
    debug("QUEUE", "Result: ", $result);
    return $result;
}

/* msg_secret
 * Return some secret data suitable for use in verifying transactions
 * associated with a message. */
function msg_secret() {
    global $fyr_queue_client;
    debug("QUEUE", "Getting secret");
    $result = $fyr_queue_client->call('FYR.Queue.secret', array());
    debug("QUEUE", "Retrieved (very hush-hush) secret");
    return $result;
}

/* msg_confirm_email TOKEN
 * Pass the TOKEN, which has been supplied by the user in a URL parameter or
 * whatever, to the queue to confirm the user's email address. Returns true on
 * success or false on failure. */
function msg_confirm_email($token) {
    global $fyr_queue_client;
    debug("QUEUE", "Confirming email");
    $result = $fyr_queue_client->call('FYR.Queue.confirm_email', array($token));
    debug("QUEUE", "Result:", $result);
    return $result;
}

/* msg_admin_recent_events
 * Returns array of hashes of recent queue events. */
function msg_admin_recent_events($count) {
    global $fyr_queue_client;
    $result = $fyr_queue_client->call('FYR.Queue.admin_recent_events', array($count));
    return $result;
}

/* msg_admin_get_queue
 * Returns array of hashes of recent queue events. */
function msg_admin_get_queue() {
    global $fyr_queue_client;
    $result = $fyr_queue_client->call('FYR.Queue.admin_get_queue', array());
    return $result;
}


?>
