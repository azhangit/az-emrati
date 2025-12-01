# Instagram Feed Integration Setup Guide

This guide will help you set up Instagram feed integration for your Laravel e-commerce application.

## 📋 Prerequisites

1. **Instagram Business Account** or **Instagram Creator Account**
   - Your Instagram account must be converted to Business or Creator account
   - Go to Instagram Settings → Account → Switch to Professional Account

2. **Facebook Page**
   - Your Instagram Business Account must be connected to a Facebook Page
   - Go to Instagram Settings → Account → Linked Accounts → Facebook

3. **Meta Developer Account**
   - Create an account at [developers.facebook.com](https://developers.facebook.com)

## 🚀 Step-by-Step Setup

### Step 1: Create a Facebook App

1. Go to [Meta for Developers](https://developers.facebook.com/apps/)
2. Click **"Create App"**
3. Select **"Business"** as the app type
4. Fill in:
   - **App Name**: Your app name (e.g., "My E-commerce Instagram Feed")
   - **Contact Email**: Your email
5. Click **"Create App"**

### Step 2: Add Instagram Basic Display Product

1. In your app dashboard, go to **"Add Products"** or **"Products"** in the left sidebar
2. Find **"Instagram Basic Display"** and click **"Set Up"**
3. Follow the setup wizard

### Step 3: Configure Instagram Basic Display

1. Go to **Settings** → **Basic** in your app dashboard
2. Add **Valid OAuth Redirect URIs**:
   ```
   https://yourdomain.com/instagram/callback
   ```
   (You can use `http://localhost` for local testing)

3. Go to **Products** → **Instagram Basic Display** → **Settings**
4. Add **Valid OAuth Redirect URIs** (same as above)
5. Click **"Deauthorize Callback URL"** and add:
   ```
   https://yourdomain.com/instagram/deauthorize
   ```

### Step 4: Get Access Token

#### Option A: Using Graph API Explorer (Recommended for Testing)

1. Go to [Graph API Explorer](https://developers.facebook.com/tools/explorer/)
2. Select your app from the dropdown
3. Click **"Generate Access Token"**
4. Select these permissions:
   - `instagram_basic`
   - `pages_read_engagement`
   - `pages_show_list`
5. Copy the generated token

#### Option B: Using Long-Lived Token (Recommended for Production)

1. First, get a short-lived token using Graph API Explorer (as above)
2. Exchange it for a long-lived token using this URL:
   ```
   https://graph.facebook.com/v19.0/oauth/access_token?grant_type=fb_exchange_token&client_id=YOUR_APP_ID&client_secret=YOUR_APP_SECRET&fb_exchange_token=SHORT_LIVED_TOKEN
   ```
3. Replace:
   - `YOUR_APP_ID` with your Facebook App ID
   - `YOUR_APP_SECRET` with your Facebook App Secret (found in Settings → Basic)
   - `SHORT_LIVED_TOKEN` with the token from Step 1
4. The response will contain a `access_token` field - this is your long-lived token (valid for ~60 days)

### Step 5: Get Instagram User ID

Run this command in your project:

```bash
php artisan instagram:get-user-id --token=YOUR_ACCESS_TOKEN
```

Or if you've already set `INSTAGRAM_ACCESS_TOKEN` in `.env`:

```bash
php artisan instagram:get-user-id
```

This will display your Instagram Business Account User ID.

### Step 6: Configure Environment Variables

Add these variables to your `.env` file:

```env
# Instagram Feed Integration
INSTAGRAM_ACCESS_TOKEN=your_long_lived_access_token_here
INSTAGRAM_USER_ID=your_instagram_user_id_here
INSTAGRAM_MEDIA_LIMIT=8
INSTAGRAM_CACHE_TTL=900
INSTAGRAM_GRAPH_VERSION=v19.0
```

**Variables Explained:**
- `INSTAGRAM_ACCESS_TOKEN`: Your long-lived access token from Step 4
- `INSTAGRAM_USER_ID`: Your Instagram Business Account ID from Step 5
- `INSTAGRAM_MEDIA_LIMIT`: Number of posts to display (default: 8)
- `INSTAGRAM_CACHE_TTL`: Cache duration in seconds (default: 900 = 15 minutes)
- `INSTAGRAM_GRAPH_VERSION`: Facebook Graph API version (default: v19.0)

### Step 7: Clear Configuration Cache

After updating `.env`, run:

```bash
php artisan config:cache
```

Or if in development:

```bash
php artisan config:clear
```

### Step 8: Test the Connection

Run the test command:

```bash
php artisan instagram:test
```

This will:
- ✅ Verify your configuration
- ✅ Test API connection
- ✅ Display sample media items
- ✅ Test caching

If everything is successful, you'll see your Instagram posts listed.

### Step 9: View on Website

Visit your website's homepage. The Instagram feed should automatically load and display your recent posts.

## 🔧 Troubleshooting

### Error: "Instagram credentials are missing"

**Solution:** Make sure all required environment variables are set in `.env` and run `php artisan config:cache`

### Error: "Invalid OAuth access token" (Error Code: 190)

**Possible causes:**
- Token has expired (short-lived tokens expire in ~1 hour)
- Token is invalid or revoked

**Solution:**
1. Generate a new access token
2. For production, use a long-lived token (valid for ~60 days)
3. Update `INSTAGRAM_ACCESS_TOKEN` in `.env`
4. Run `php artisan config:cache`

### Error: "Invalid user ID" (Error Code: 100)

**Solution:**
1. Run `php artisan instagram:get-user-id` to get the correct User ID
2. Update `INSTAGRAM_USER_ID` in `.env`
3. Run `php artisan config:cache`

### Error: "No Instagram posts available"

**Possible causes:**
- Account has no posts
- Posts are private
- Token doesn't have required permissions

**Solution:**
1. Make sure your Instagram account has public posts
2. Verify token has `instagram_basic` permission
3. Check if account is Business/Creator account

### Feed Not Loading on Frontend

**Check:**
1. Browser console for JavaScript errors
2. Network tab for API call to `/instagram/feed`
3. Run `php artisan instagram:test` to verify backend is working
4. Check if cache is working: `php artisan cache:clear`

## 🔄 Token Refresh (Important!)

Long-lived tokens expire after ~60 days. To avoid service interruption:

1. **Set a reminder** to refresh your token before it expires
2. **Refresh token** using:
   ```
   https://graph.facebook.com/v19.0/oauth/access_token?grant_type=fb_exchange_token&client_id=YOUR_APP_ID&client_secret=YOUR_APP_SECRET&fb_exchange_token=CURRENT_TOKEN
   ```
3. **Update** `INSTAGRAM_ACCESS_TOKEN` in `.env`
4. **Run** `php artisan config:cache`

## 📝 Available Commands

### Test Instagram Connection
```bash
php artisan instagram:test
```
Tests the connection and displays feed status.

### Get Instagram User ID
```bash
php artisan instagram:get-user-id --token=YOUR_TOKEN
```
Retrieves your Instagram Business Account User ID.

## 🎨 Customization

### Change Number of Posts

Edit `.env`:
```env
INSTAGRAM_MEDIA_LIMIT=12
```

### Change Cache Duration

Edit `.env`:
```env
INSTAGRAM_CACHE_TTL=1800  # 30 minutes
```

### Customize Frontend Display

Edit: `resources/views/frontend/classic/index.blade.php`

The Instagram feed section starts around line 1486. You can customize:
- Grid layout (CSS in `<style>` tag)
- Number of columns
- Card design
- Colors and styling

## 📚 Additional Resources

- [Instagram Basic Display API Documentation](https://developers.facebook.com/docs/instagram-basic-display-api)
- [Facebook Graph API Explorer](https://developers.facebook.com/tools/explorer/)
- [Meta for Developers](https://developers.facebook.com/)

## ✅ Checklist

- [ ] Instagram account converted to Business/Creator
- [ ] Instagram account linked to Facebook Page
- [ ] Facebook App created
- [ ] Instagram Basic Display product added
- [ ] Access token obtained (long-lived for production)
- [ ] Instagram User ID retrieved
- [ ] Environment variables configured
- [ ] Configuration cache cleared
- [ ] Connection tested successfully
- [ ] Feed visible on website

## 🆘 Need Help?

If you encounter issues:
1. Run `php artisan instagram:test` for diagnostics
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify all environment variables are set correctly
4. Ensure token has required permissions

---

**Last Updated:** January 2025

