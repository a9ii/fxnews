<?php
date_default_timezone_set('Asia/Baghdad'); // تعيين التوقيت إلى بغداد

$apiToken = "YOUR_BOT_API_TOKEN";  // ضع التوكن الخاص بالبوت هنا
$chatId = "@YourChannelID";  // ضع معرف القناة هنا للنشر
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
    $today = date('Y-m-d');  // الحصول على تاريخ اليوم

    $todaysNews = [];

    foreach ($events as $event) {
        // تحويل التوقيت من GMT إلى توقيت بغداد
        $eventDate = new DateTime($event['date'], new DateTimeZone('GMT'));
        $eventDate->setTimezone(new DateTimeZone('Asia/Baghdad'));

        // التحقق من أن الدولة هي الولايات المتحدة USD وأن التأثير High
        if ($eventDate->format('Y-m-d') == $today && $event['country'] == "USD" && $event['impact'] == "High") {
            $event['date'] = $eventDate->format('g:i A'); // تحويل التوقيت إلى 12 ساعة بتوقيت بغداد
            $todaysNews[] = $event;
        }
    }

    return $todaysNews;
}

// وظيفة للتحقق من الأخبار الخاصة باليوم ونشرها
function checkAndSendTodaysNews($apiToken, $chatId, $jsonFile) {
    $news = getTodaysNewsFromFile($jsonFile);
    
    if (!$news || count($news) == 0) {
        sendMessage($chatId, "لا توجد أخبار اقتصادية مهمة للولايات المتحدة اليوم.", $apiToken);
        return;
    }

    foreach ($news as $item) {
        $message = "الخبر: " . $item['title'] . "\n";
        $message .= "الوقت: " . $item['date'] . "\n"; // إظهار التوقيت بصيغة 12 ساعة بتوقيت بغداد
        $message .= "التأثير: " . $item['impact'] . "\n";
        
        sendMessage($chatId, $message, $apiToken);
    }
}

// وظيفة للتحقق من الأخبار التي تحدث خلال 5 دقائق
function checkUpcomingNews($apiToken, $chatId, $jsonFile) {
    $news = getTodaysNewsFromFile($jsonFile);
    $currentTime = date('H:i');
    
    foreach ($news as $item) {
        $newsTime = new DateTime($item['date'], new DateTimeZone('Asia/Baghdad'));
        $newsTime->modify('-5 minutes');
        $formattedNewsTime = $newsTime->format('H:i');
        
        if ($currentTime == $formattedNewsTime) {
            $message = "تنبيه: الخبر التالي سيصدر بعد 5 دقائق: " . $item['title'] . "\n";
            $message .= "الوقت: " . $item['date'] . "\n";
            sendMessage($chatId, $message, $apiToken);
        }
    }
}

// وظيفة للتحقق من وقت الساعة 12:00 لنشر جميع الأخبار
function checkAndSendDailyNews($apiToken, $chatId, $jsonFile) {
    $currentHour = date('H:i');
    if ($currentHour == "12:00") {  // نشر جميع الأخبار في الساعة 12:00
        checkAndSendTodaysNews($apiToken, $chatId, $jsonFile);
    }
}

// وظيفة لتحميل الأخبار في بداية كل ساعة
function loadNewsEveryHour($jsonUrl, $jsonFile, $chatId, $apiToken) {
    $currentMinute = date('i');
    $userId = "YOUR_TELEGRAM_USER_ID"; // معرف المستخدم الذي سيتم إرسال الرسائل إليه
    
    if ($currentMinute == "00") {  // تحقق أن الدقيقة هي "00" لبداية الساعة
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
if (loadNewsEveryHour($jsonUrl, $jsonFile, $chatId, $apiToken)) {  // تحميل الأخبار في بداية كل ساعة
    checkUpcomingNews($apiToken, $chatId, $jsonFile);  // نشر التنبيه قبل 5 دقائق من صدور الخبر
}
checkAndSendDailyNews($apiToken, $chatId, $jsonFile);  // نشر الأخبار عند الساعة 12:00

?>
