<?php
// টেস্ট করার জন্য 'sandbox' ডোমেইন দেওয়া আছে। লাইভ করার সময় URL পরিবর্তন করতে হবে।
define('BKASH_BASE_URL', 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized');

// বিকাশের দেয়া মার্চেন্ট তথ্য এগুলো এখানে বসাবেন
define('BKASH_APP_KEY', 'YOUR_BKASH_APP_KEY');
define('BKASH_APP_SECRET', 'YOUR_BKASH_APP_SECRET');
define('BKASH_USERNAME', 'YOUR_BKASH_USERNAME');
define('BKASH_PASSWORD', 'YOUR_BKASH_PASSWORD');

// পেমেন্ট শেষে যেখানে ফিরে আসবে (আপনার ওয়েবসাইটের লিংক)
define('CALLBACK_URL', 'http://localhost/callback.php'); 
?>
