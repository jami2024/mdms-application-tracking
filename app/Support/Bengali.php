<?php

namespace App\Support;

class Bengali
{
    // Maps the English enum/status words stored in the database (application
    // status, payment status, module names, etc.) to their Bengali display
    // label. Falls back to ucfirst($value) for anything not in the map, so
    // it's always safe to swap ucfirst($x) -> Bengali::label($x) in a view.
    private static array $map = [
        // application / applicant-module status
        'draft' => 'খসড়া',
        'submitted' => 'জমাকৃত',
        'in_review' => 'পর্যালোচনাধীন',
        'returned' => 'ফেরত',
        'approved' => 'অনুমোদিত',
        'rejected' => 'প্রত্যাখ্যাত',
        'active' => 'সক্রিয়',
        'inactive' => 'নিষ্ক্রিয়',
        'suspended' => 'স্থগিত',
        'pending' => 'অপেক্ষমাণ',
        'registered' => 'নিবন্ধিত',
        'expired' => 'মেয়াদোত্তীর্ণ',
        'revoked' => 'বাতিলকৃত',

        // payment status
        'paid' => 'পরিশোধিত',
        'reconciled' => 'সমন্বিত',
        'failed' => 'ব্যর্থ',
        'processing' => 'প্রক্রিয়াধীন',
        'completed' => 'সম্পন্ন',

        // user status / type
        'admin' => 'অ্যাডমিন',
        'staff' => 'স্টাফ',
        'applicant' => 'আবেদনকারী',

        // applicant modules
        'company' => 'প্রতিষ্ঠান',
        'establishment' => 'এস্টাবলিশমেন্ট',
        'device' => 'ডিভাইস',
        'mrp' => 'এমআরপি',

        // workflow step action_type
        'review' => 'পর্যালোচনা',
        'approve' => 'অনুমোদন',
        'reject' => 'প্রত্যাখ্যান',
        'forward' => 'ফরওয়ার্ড',
        'sign' => 'স্বাক্ষর',
        'backward' => 'ফেরত পাঠানো',
        'submit' => 'জমা',

        // certificate signature type
        'digital' => 'ডিজিটাল',
        'uploaded' => 'আপলোডকৃত',

        // activity log event
        'created' => 'তৈরি হয়েছে',
        'updated' => 'হালনাগাদ হয়েছে',
        'deleted' => 'মুছে ফেলা হয়েছে',

        // import/export log
        'import' => 'ইমপোর্ট',
        'export' => 'এক্সপোর্ট',
    ];

    public static function label(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return self::$map[$value] ?? ucfirst(str_replace('_', ' ', $value));
    }
}
