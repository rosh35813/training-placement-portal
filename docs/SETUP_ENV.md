# Environment Configuration Setup

## Quick Start

1. **Copy the example file:**
   ```bash
   cp env.example .env
   ```

2. **Edit `.env` file** with your actual credentials:
   ```bash
   # Windows
   notepad .env
   
   # Linux/Mac
   nano .env
   ```

3. **Update email settings:**
   ```env
   TNP_SMTP_EMAIL=your-email@gmail.com
   TNP_SMTP_PASS=your-app-password
   ```

## Email Configuration

### Gmail Setup

1. **Enable 2-Step Verification** on your Google Account
2. **Generate App Password:**
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" and "Other (Custom name)"
   - Enter "T&P Portal" as the name
   - Copy the 16-character password

3. **Update `.env` file:**
   ```env
   TNP_SMTP_EMAIL=your-email@gmail.com
   TNP_SMTP_PASS=xxxx xxxx xxxx xxxx  # Your 16-char app password
   ```

### Other Email Providers

#### Outlook/Hotmail
```env
TNP_SMTP_HOST=smtp-mail.outlook.com
TNP_SMTP_PORT=587
TNP_SMTP_SECURE=tls
TNP_SMTP_EMAIL=your-email@outlook.com
TNP_SMTP_PASS=your-password
```

#### Yahoo Mail
```env
TNP_SMTP_HOST=smtp.mail.yahoo.com
TNP_SMTP_PORT=587
TNP_SMTP_SECURE=tls
TNP_SMTP_EMAIL=your-email@yahoo.com
TNP_SMTP_PASS=your-app-password
```

#### Custom SMTP Server
```env
TNP_SMTP_HOST=mail.yourdomain.com
TNP_SMTP_PORT=587
TNP_SMTP_SECURE=tls
TNP_SMTP_EMAIL=noreply@yourdomain.com
TNP_SMTP_PASS=your-password
```

## Database Configuration

Update database settings in `.env`:

```env
TNP_DB_HOST=localhost
TNP_DB_USER=your_db_user
TNP_DB_PASS=your_db_password
TNP_DB_NAME=placement
```

## Production Settings

For production, update these settings:

```env
TNP_ENVIRONMENT=production
TNP_DISPLAY_ERRORS=0
TNP_BASE_URL=https://yourdomain.com/TnP
```

## Security Notes

- ⚠️ **Never commit `.env` file to version control**
- ✅ The `.env` file is already in `.gitignore`
- ✅ Use `env.example` as a template
- ✅ Keep `.env` file permissions restricted (600 on Linux/Mac)

## Verification

After setting up `.env`, test email functionality:

1. Try password reset feature
2. Check `email_log.txt` if email fails
3. Verify SMTP credentials are correct

## Troubleshooting

### Email not sending?

1. Check `.env` file exists and is readable
2. Verify SMTP credentials are correct
3. For Gmail, ensure you're using App Password (not regular password)
4. Check firewall/network allows SMTP connections
5. Review `email_log.txt` for error messages

### Environment variables not loading?

1. Ensure `load_env.php` is included before `config.php`
2. Check `.env` file syntax (no spaces around `=`)
3. Verify file permissions
4. Check PHP error logs

