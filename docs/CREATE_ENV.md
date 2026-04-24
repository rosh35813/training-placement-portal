# Quick Setup: Create .env File

## Step 1: Create .env File

Copy the example file to create your `.env` file:

### Windows (Command Prompt)
```cmd
copy env.example .env
```

### Windows (PowerShell)
```powershell
Copy-Item env.example .env
```

### Linux/Mac
```bash
cp env.example .env
```

## Step 2: Edit .env File

Open `.env` in a text editor and update the email settings:

```env
# Email Configuration
TNP_SMTP_EMAIL=your-email@gmail.com
TNP_SMTP_PASS=your-app-password
```

### For Gmail Users:

1. Go to: https://myaccount.google.com/apppasswords
2. Generate an App Password
3. Copy the 16-character password
4. Paste it in `.env` file as `TNP_SMTP_PASS`

## Step 3: Verify

The `.env` file should now be in your project root:
```
TnP/
├── .env          ← Your new file
├── env.example   ← Template file
├── config.php
└── ...
```

## Important Notes

- ✅ `.env` is already in `.gitignore` (won't be committed)
- ✅ Keep `env.example` in version control (as template)
- ⚠️ Never share your `.env` file or commit it to Git
- ✅ The application will automatically load `.env` when you include `config.php`

## Testing

After creating `.env`, test email functionality:
1. Try the password reset feature
2. Check if emails are sent
3. Review `email_log.txt` if there are issues

