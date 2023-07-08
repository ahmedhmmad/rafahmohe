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
