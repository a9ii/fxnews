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
