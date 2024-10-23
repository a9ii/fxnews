<?php
// تعيين التوقيت الافتراضي إلى UTC لتجنب أي تعارضات
date_default_timezone_set('UTC');

$apiToken = "YOUR_TELEGRAM_BOT_TOKEN";  // ضع التوكن الخاص بالبوت هنا
$chatId = "@YOUR_CHANNEL_ID";  // ضع معرف القناة هنا
$jsonUrl = "https://nfs.faireconomy.media/ff_calendar_thisweek.json"; // مصدر الأخبار
$jsonFile = 'ff_calendar_thisweek.json'; // ملف لحفظ الأخبار

// الوظيفة التي تقوم بإرسال الرسالة
function sendMessage($chatId, $message, $apiToken) {
    $url = "https://api.telegram.org/bot$apiToken/sendMessage";
    $postData = [
        'chat_id' => $chatId,
        'text' => $message
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

// تحميل وحفظ ملف JSON باستخدام cURL
function fetchAndSaveJsonData($url, $file) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // تعطيل فحص SSL إذا كانت هناك مشكلة
    $data = curl_exec($ch);
    curl_close($ch);
    
    if ($data) {
        file_put_contents($file, $data); // حفظ البيانات في الملف
        return true;
    }
    
    return false;
}

// قراءة الأخبار من الملف المحفوظ وتحويل التوقيت إلى توقيت بغداد
function getTodaysNewsFromFile($file) {
    if (!file_exists($file)) {
        return null;
    }

    $jsonData = file_get_contents($file);
    $events = json_decode($jsonData, true);
    $baghdadTimezone = new DateTimeZone('Asia/Baghdad');
    $today = new DateTime('now', $baghdadTimezone);  // الحصول على تاريخ اليوم بتوقيت بغداد
    $todayDate = $today->format('Y-m-d');

    $todaysNews = [];

    foreach ($events as $event) {
        // تحويل التوقيت من GMT إلى توقيت بغداد
        $eventDate = new DateTime($event['date'], new DateTimeZone('GMT'));
        $eventDate->setTimezone($baghdadTimezone);

        // التحقق من أن الدولة هي الولايات المتحدة USD وأن التأثير High
        if ($eventDate->format('Y-m-d') == $todayDate && $event['country'] == "USD" && $event['impact'] == "High") {
            $event['time_formatted'] = $eventDate->format('g:i A'); // تحويل التوقيت إلى 12 ساعة بتوقيت بغداد
            $event['datetime'] = $eventDate; // حفظ كائن DateTime للاستخدام لاحقًا
            $todaysNews[] = $event;
        }
    }

    return $todaysNews;
}

// وظيفة للتحقق من الأخبار الخاصة باليوم ونشرها في الساعة 12:00
function checkAndSendTodaysNews($apiToken, $chatId, $jsonFile) {
    $news = getTodaysNewsFromFile($jsonFile);
    
    if (!$news || count($news) == 0) {
        sendMessage($chatId, "لا توجد أخبار اقتصادية مهمة للولايات المتحدة اليوم.", $apiToken);
        return;
    }

    foreach ($news as $item) {
        $message = "الخبر: " . $item['title'] . "\n";
        $message .= "الوقت: " . $item['time_formatted'] . "\n"; // إظهار التوقيت بصيغة 12 ساعة بتوقيت بغداد
        $message .= "التأثير: " . $item['impact'] . "\n";
        $message .= "التوقعات (Forecast): " . $item['forecast'] . "\n";
        $message .= "السابقة (Previous): " . $item['previous'] . "\n";
        
        sendMessage($chatId, $message, $apiToken);
    }
}

// وظيفة للتحقق من الأخبار التي ستحدث خلال 5 دقائق
function checkUpcomingNews($apiToken, $chatId, $jsonFile) {
    $news = getTodaysNewsFromFile($jsonFile);
    $baghdadTimezone = new DateTimeZone('Asia/Baghdad');
    $currentTime = new DateTime("now", $baghdadTimezone); // الوقت الحالي بتوقيت بغداد

    foreach ($news as $item) {
        $newsTime = $item['datetime'];

        if ($newsTime) {
            $interval = $currentTime->diff($newsTime);
            $minutesDifference = ($interval->h * 60) + $interval->i;

            // تحقق ما إذا كان الفرق 5 دقائق والحدث لم يحدث بعد
            if ($interval->invert == 0 && $minutesDifference == 5) {
                $message = "تنبيه: الخبر التالي سيصدر بعد 5 دقائق: " . $item['title'] . "\n";
                $message .= "الوقت: " . $item['time_formatted'] . "\n";
                $message .= "التوقعات (Forecast): " . $item['forecast'] . "\n";
                $message .= "السابقة (Previous): " . $item['previous'] . "\n";
                sendMessage($chatId, $message, $apiToken);
            }
        }
    }
}

// وظيفة للتحقق من وقت الساعة 12:00 لنشر جميع الأخبار
function checkAndSendDailyNews($apiToken, $chatId, $jsonFile) {
    $baghdadTimezone = new DateTimeZone('Asia/Baghdad');
    $currentTime = new DateTime('now', $baghdadTimezone);
    $currentHourMinute = $currentTime->format('H:i');
    if ($currentHourMinute == "12:00") {  // نشر جميع الأخبار في الساعة 12:00 بتوقيت بغداد
        checkAndSendTodaysNews($apiToken, $chatId, $jsonFile);
    }
}

function loadNewsEveryHour($jsonUrl, $jsonFile, $apiToken) {
    $baghdadTimezone = new DateTimeZone('Asia/Baghdad');
    $currentTime = new DateTime('now', $baghdadTimezone);
    $currentMinute = $currentTime->format('i');
    $userId = "YOUR_TELEGRAM_USER_ID"; // معرف المستخدم الذي سيتم إرسال الرسائل إليه

    if ($currentMinute == "45") {  // تحقق أن الدقيقة هي "45" لبداية الساعة بتوقيت بغداد
        if (fetchAndSaveJsonData($jsonUrl, $jsonFile)) {
            sendMessage($userId, "تم تحميل وحفظ الأخبار بنجاح.", $apiToken); // رسالة لتأكيد التحميل إلى المعرف الشخصي
            return true;
        } else {
            sendMessage($userId, "فشل تحميل الأخبار.", $apiToken); // رسالة إذا فشل التحميل إلى المعرف الشخصي
        }
    }
    return false;
}

// استدعاء الدوال المناسبة
loadNewsEveryHour($jsonUrl, $jsonFile, $apiToken); // تحميل الأخبار كل ساعة
checkUpcomingNews($apiToken, $chatId, $jsonFile);  // التحقق من الأخبار القادمة خلال 5 دقائق
checkAndSendDailyNews($apiToken, $chatId, $jsonFile);  // نشر الأخبار عند الساعة 12:00 بتوقيت بغداد

?>
