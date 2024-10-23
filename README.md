# 📊 Economic News Telegram Bot 🚀

This PHP script automates the process of fetching and sending important economic news updates to a Telegram channel or user. It retrieves data from a JSON source, filters news based on specific criteria (such as country and impact level), and schedules notifications for critical updates. Perfect for keeping your audience informed about key financial events in real-time! 💡

## 🌟 Features
- **📥 Fetch Economic News:** Automatically retrieves economic data from an external source every hour.
- **⏰ Time Zone Conversion:** Converts event times from GMT to Baghdad time (Asia/Baghdad) to ensure local relevance.
- **🗓️ Scheduled Announcements:** Sends important economic news at 12:00 PM Baghdad time directly to your Telegram channel.
- **⏳ 5-Minute Alerts:** Notifies the channel 5 minutes before critical economic news is released.
- **🔄 Hourly Data Updates:** Fetches and saves the latest news data every hour, and notifies the user of successful or failed updates.

## 🛠️ Setup Instructions

### 1. **Clone the Repository:**
```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
cd YOUR_REPOSITORY
```

### 2. **Configure the Script:**
- Open the PHP script and modify the following lines with your own credentials:
  - Replace `YOUR_TELEGRAM_BOT_TOKEN` with your bot's API token.
  - Replace `@YOUR_CHANNEL_ID` with your Telegram channel's chat ID.
  - Replace `YOUR_TELEGRAM_USER_ID` with your Telegram user ID to receive alerts when data is fetched.

  ```php
  $apiToken = "YOUR_TELEGRAM_BOT_TOKEN";  // 🔐 Your bot token
  $chatId = "@YOUR_CHANNEL_ID";  // 📨 Your channel ID or user ID
  $userId = "YOUR_TELEGRAM_USER_ID";  // 📨 Your user ID for notifications
  ```

### 3. **Set Up a Cron Job (Optional):**
To automate the script execution, schedule it to run every 5 minutes using cron jobs:
```bash
* * * * * /usr/bin/php /path_to_your_script/bot.php
```
This ensures the bot checks for news updates and sends alerts on time. ⏳

### 4. **Run the Script:**
You can manually run the script with PHP for testing purposes:
```bash
php bot.php
```

## 🔍 How It Works

1. **Hourly News Fetching:** Every hour, the script calls `fetchAndSaveJsonData()` to pull fresh economic data from the JSON source and saves it to a local file.
2. **Daily News Check:** At 12:00 PM Baghdad time, the bot filters the day's news for high-impact events in the US and sends them to the Telegram channel.
3. **5-Minute Alerts:** The script checks if any critical news is due within 5 minutes and sends out a pre-release notification. ⏰

## 🔧 Example Output

Here’s an example of a message sent by the bot to the Telegram channel:

```
📢 **News:** US GDP Growth Rate 🇺🇸
🕒 **Time:** 2:30 PM
⚡ **Impact:** High
📊 **Forecast:** 2.1%
📉 **Previous:** 2.0%
```

## 🌐 Customization

- **Time Zone:** You can change the time zone by modifying the `DateTimeZone('Asia/Baghdad')` object to any other region.
- **News Criteria:** The bot currently filters for high-impact US news. You can adjust the filters in the `getTodaysNewsFromFile()` function to include different countries or impact levels.

## 📝 License

This project is open-source and available under the MIT License. Feel free to fork, contribute, or modify the script to suit your needs.

---

🚀 **Start keeping your audience informed with real-time economic updates!**
