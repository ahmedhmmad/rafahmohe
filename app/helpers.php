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
        default:
            return '';
    }
}

    function getCloseReason($closeReason)
    {
        switch ($closeReason) {
            case 'work_completed':
                return 'تم انجاز العمل';
            case 'transferred_to_general_management':
                return 'تم تحويلها للادارة العامة';
            case 'out_of_scope':
                return 'خارج صلاحيات القسم';
            case 'no_money':
                return 'لا يوجد ميزانية';
            default:
                return '';
        }

}
