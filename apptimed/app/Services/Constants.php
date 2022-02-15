<?php

namespace App\Services;

class Constants
{
    //DAYS CONSTANTS ==============================
    public static $DEFAULT_DAYS_DUE_FOR_PAYMENT = 7;
    public static $DEFAULT_DAYS_DUE_FOR_PUBLISHING_AFTER_PAYMENT = 21;
    public static $DEFAULT_DAYS_DUE_FOR_ARTICLE_WRITING = 7;

    //ORDER STATUSES ==============================
    public static $ORDER_STATUS_OPEN = "OPEN";
    public static $ORDER_STATUS_PENDING_FOR_WRITER_APPROVAL = "PENDING_FOR_WRITER_APPROVAL";
    public static $ORDER_STATUS_PENDING_FOR_PAYMENT = "PENDING_FOR_PAYMENT";
    public static $ORDER_STATUS_APPROVED = "APPROVED";
    public static $ORDER_STATUS_DISAPPROVED = "DISAPPROVED";
    public static $ORDER_STATUS_FLAGGED = "FLAGGED";
    public static $ORDER_STATUS_WRITING_PENDING = "WRITING_PENDING";
    public static $ORDER_STATUS_WRITING_IN_PROGRESS = "WRITING_IN_PROGRESS";
    public static $ORDER_STATUS_WRITING_DONE = "WRITING_DONE";
    public static $ORDER_STATUS_CANCELLED_BY_CUSTOMER = "CANCELLED_BY_CUSTOMER";
    public static $ORDER_STATUS_CANCELLED_BY_EMPLOYEE = "CANCELLED_BY_EMPLOYEE";
    public static $ORDER_STATUS_COMPLETED = "COMPLETED";

    public static $PAYMENT_STATUS_PAID = "Paid";
    public static $PAYMENT_STATUS_PENDING = "Pending";

    public static $CUSTOMER_STATUS_LABELS = array(
        "OPEN"=>"Open",
        "PENDING_FOR_WRITER_APPROVAL"=>"Waiting for approval",
        "PENDING_FOR_PAYMENT"=>"Pending for Payment",
        "FLAGGED"=>"Flagged",
        "APPROVED"=>"Approved by the writer",
        "DISAPPROVED"=>"Waiting for approval",
        "WRITING_PENDING"=>"Writing in progress",
        "WRITING_IN_PROGRESS"=>"Writing in progress",
        "WRITING_DONE"=>"Waiting for publishment",
        "CANCELLED_BY_CUSTOMER"=>"Cancelled",
        "CANCELLED_BY_EMPLOYEE"=>"Cancelled",
        "COMPLETED"=>"Completed"
    );

    public static $INTERNAL_STATUS_LABELS = array(
        "OPEN"=>"Open",
        "PENDING_FOR_WRITER_APPROVAL"=>"Pending for Writer Approval",
        "PENDING_FOR_PAYMENT"=>"Invoice Sent",
        "APPROVED"=>"Approved",
        "DISAPPROVED"=>"Disapproved",
        "FLAGGED"=>"Flagged",
        "WRITING_PENDING"=>"Waiting for Writer to start",
        "WRITING_IN_PROGRESS"=>"Writing in progress",
        "WRITING_DONE"=>"Writing done",
        "CANCELLED_BY_CUSTOMER"=>"Cancelled",
        "CANCELLED_BY_EMPLOYEE"=>"Cancelled",
        "COMPLETED"=>"Completed"
    );

    public static $TASK_STATUS_LABELS = array(
        "WRITING_PENDING"=>"Pending",
        "WRITING_IN_PROGRESS"=>"In Progress",
        "WRITING_DONE"=>"Completed",
        "COMPLETED"=>"Published",
    );


}
