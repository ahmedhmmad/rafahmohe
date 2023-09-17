<?php

function getStatusStyle($status)
{
    switch ($status) {
        case 'open':
            return 'bg-label-primary';
        case 'assigned':
            return 'bg-label-success';
        case 'on-progress':
            return 'bg-label-warning';
        case 'closed':
            return 'bg-label-danger';
        case 'partially-closed':
            return 'bg-label-secondary';
        default:
            return '';
    }
}

function getStatusName($status)
{
    switch ($status) {
        case 'open':
            return 'جديدة';
        case 'assigned':
            return 'تم التعيين';
        case 'on-progress':
            return 'قيد التنفيذ';
        case 'closed':
            return 'مغلقة';
        case 'partially-closed':
            return 'مغلقة جزئيا';
        default:
            return '';
    }
}

function getCloseReason($closeReason)
{
    switch ($closeReason) {
        case 'work_completed':
            return 'تم انجاز العمل';
        case 'partially-closed':
            return 'تم انجاز العمل جزئياً';
        case 'transferred_to_general_management':
            return 'تم تحويلها للادارة العامة';
        case 'out_of_scope':
            return 'خارج صلاحيات القسم';
        case 'no_money':
            return 'لا يوجد ميزانية';
        case 'no_technician':
            return 'لا يوجد فني';
        default:
            // If the reason doesn't match any of the predefined cases, return the input reason itself
            return $closeReason ? $closeReason : 'غير محدد';
    }



}
