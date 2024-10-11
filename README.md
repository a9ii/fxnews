```markdown
# 📰 Forex News Bot for Telegram 📊

A Telegram bot that fetches high-impact economic news related to USD currency from [Forex Factory](https://nfs.faireconomy.media/ff_calendar_thisweek.json) and sends updates directly to a Telegram channel. It checks news at the start of every hour, and posts a reminder 5 minutes before an important event is about to happen.

## 🚀 Features
- 📥 Fetches news automatically from the **Forex Factory** JSON feed.
- ⏰ Converts the time from GMT to **Baghdad local time** (Asia/Baghdad timezone).
- 📢 Posts important USD economic news in the channel daily at **12:00 PM Baghdad time**.
- ⚠️ Sends a reminder 5 minutes before the news happens.
- ✅ Stores the news data in a local JSON file for efficient processing.
- 🔄 News is checked every hour, and updates are sent to a specific user about the status of the data fetching.

## 🛠️ Installation & Setup

### Requirements
- PHP 7.x or higher
- cURL extension enabled
- A Telegram Bot API token (you can get it from [BotFather](https://core.telegram.org/bots#botfather))
- Access to a server with Cron jobs enabled

### Steps to Install
1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/forex-news-bot.git
   cd forex-news-bot
```
   ```

2. Update the bot configuration:
   - Open the `bot.php` file and set your Telegram Bot API token and channel ID.
   - Replace the following placeholders:
     ```php
     $apiToken = "YOUR_BOT_API_TOKEN";  
     $chatId = "@YourChannelID";  // Channel where news will be posted
     ```

3. Ensure the `Cron job` is configured to run the bot every minute:
   ```bash
   * * * * * /usr/bin/php /path/to/your/project/bot.php
   ```

4. The bot should now fetch news at the start of every hour, check for important events, and send updates at 12:00 PM Baghdad time.

## 🧩 Usage

- **Automatic News Updates**: The bot will automatically fetch the news and post it to your Telegram channel at 12:00 PM (Baghdad time).
- **Hourly Checks**: The bot will check the news every hour and prepare updates.
- **5-Minute Reminder**: It will send a reminder to the channel 5 minutes before a high-impact USD-related news event.
  
## 📂 Project Structure

```bash
forex-news-bot/
│
├── bot.php               # Main script that handles fetching, converting, and sending news
├── ff_calendar_thisweek.json  # Local JSON file where news is stored
└── README.md             # This file
```

## 🔧 Configuration

- **Timezone**: The timezone is automatically set to `Asia/Baghdad` for correct time conversion.
  You can adjust it in the script:
  ```php
  date_default_timezone_set('Asia/Baghdad');
  ```
  
- **User Alerts**: Status updates (such as successful data fetching or failures) are sent to a specific user ID. Change the user ID in the script:
  ```php
  $userId = "YOUR_TELEGRAM_USER_ID"; // Change this to your personal Telegram ID
  ```

## 🐛 Troubleshooting

If the bot is not working as expected:
- Ensure that your server has access to the internet to fetch the news feed.
- Check that the Cron job is correctly configured and running every minute.
- Verify that the `bot.php` file has execution permissions.
  
To debug, you can enable message logging by uncommenting the following line in the script:
```php
// sendMessage($userId, "Debugging message: Time is now " . date('H:i'), $apiToken);
```

## 🤝 Contributing
Feel free to fork this repository and submit pull requests. Any contributions that improve functionality or add features are welcome!

## 📝 License
This project is open-source and available under the MIT License.
```

### إيموجي المستخدم:
- 📰 لإبراز أن البوت مخصص للأخبار.
- 📊 لإضافة طابع مالي متعلق بالاقتصاد.
- 🚀 لإبراز المميزات الهامة.
- 📥 لتحميل الأخبار.
- ⏰ لتوضيح الفواصل الزمنية.
- 📢 للتنبيه.
- ⚠️ للتحذيرات قبل 5 دقائق.
- ✅ لتأكيد نجاح عملية التحميل.
- 🔄 لتوضيح عملية التحديث الدوري.
- 🛠️ لإبراز جزء الإعدادات.
- 🧩 لإظهار هيكلة المشروع.
- 🔧 لتخصيص التكوين.
- 🐛 للتحدث عن الأخطاء والتصحيح.
- 🤝 للتحدث عن المساهمات.

### هذا الملف مناسب لصفحة GitHub ويحتوي على جميع التفاصيل الضرورية حول المشروع.
