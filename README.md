# 📰 Forex News Bot for Telegram 📊

**Forex News Bot** is a Telegram bot that fetches high-impact economic news related to USD from [Forex Factory](https://nfs.faireconomy.media/ff_calendar_thisweek.json) and posts updates directly to a Telegram channel. The bot checks the news at the start of every hour and posts a reminder 5 minutes before an important event occurs. It also adjusts the time to Baghdad's timezone for accurate local updates.

## 🚀 Features

- 📥 **Automatically fetches news** from the **Forex Factory** JSON feed.
- ⏰ **Converts time to Baghdad local time** (Asia/Baghdad timezone).
- 📢 **Posts important USD news** to the Telegram channel daily at **12:00 PM Baghdad time**.
- ⚠️ **Sends a 5-minute reminder** before important events.
- ✅ **Stores news data locally** in a JSON file for better performance.
- 🔄 **Checks news every hour** and sends updates to a specific user if the news fetching was successful or failed.

## 🛠️ Installation & Setup

### Requirements

- **PHP 7.x or higher**
- **cURL extension** enabled in PHP
- A **Telegram Bot API token** (you can obtain it from [BotFather](https://core.telegram.org/bots#botfather))
- Access to a server with **Cron jobs** enabled

### Installation Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/forex-news-bot.git
   cd forex-news-bot


2. **Configure the bot**:
   - Open the `bot.php` file and add your **Telegram Bot API token** and the **channel ID**.
   - Replace the following lines with your values:
     ```php
     $apiToken = "YOUR_BOT_API_TOKEN";  
     $chatId = "@YourChannelID";  // Channel where the news will be posted
     ```

3. **Set up the Cron Job to run every minute**:
   - Configure the **Cron Job** to run every minute:
     ```bash
     * * * * * /usr/bin/php /path/to/your/project/bot.php
     ```

4. **Start the bot**:
   - The bot will now start fetching news at the start of every hour and post updates at **12:00 PM Baghdad time**.

## 🧩 Usage

- **Automatic News Updates**: The bot automatically fetches and posts important news to the channel at **12:00 PM** Baghdad time.
- **Hourly Checks**: The bot checks for updates every hour and posts relevant updates if available.
- **5-Minute Reminders**: The bot will send a reminder 5 minutes before important USD-related news is published.

## 📂 Project Structure

```bash
forex-news-bot/
│
├── bot.php               # The main script for fetching, converting, and posting news
├── ff_calendar_thisweek.json  # Local JSON file where news is stored
└── README.md             # This file
```

## 🔧 Configuration

- **Timezone**: The default timezone is set to `Asia/Baghdad` to ensure correct local time conversion. You can modify it in the script if necessary:
  ```php
  date_default_timezone_set('Asia/Baghdad');
  ```

- **User Alerts**: Success or failure messages for news fetching are sent to a specific user ID. To change the user ID, modify this line in the script:
  ```php
  $userId = "YOUR_TELEGRAM_USER_ID"; // Replace with your Telegram user ID
  ```

## 🐛 Troubleshooting

If the bot is not functioning as expected:
- Ensure that your server has internet access to fetch the news feed.
- Check that the **Cron Job** is set up correctly and running every minute.
- Verify that the `bot.php` file has the correct execution permissions.

To enable logging for debugging purposes, uncomment the following line in the script:
```php
// sendMessage($userId, "Debugging message: Time is now " . date('H:i'), $apiToken);
```

## 🤝 Contributing

Feel free to fork this repository and submit pull requests. Any contributions that improve functionality or add features are welcome!

## 📝 License

This project is open-source and available under the **MIT License**.

```


