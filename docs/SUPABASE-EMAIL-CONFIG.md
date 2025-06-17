# Supabase Email Confirmation Configuration

This document explains the email confirmation redirect URL configuration for the Lab Inventory application.

## 🔧 Configuration

### Environment Variables

Add these variables to your `.env` file:

```env
# Supabase Email Confirmation Redirect URLs
SUPABASE_REDIRECT_URL=${APP_URL}/auth/callback
SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=${APP_URL}/auth/confirm
```

### Automatic URL Matching

The redirect URLs automatically use your `APP_URL` environment variable:

- **Development**: `http://localhost:8000/auth/confirm`
- **Docker**: `http://localhost:8080/auth/confirm`
- **Production**: `https://yourdomain.com/auth/confirm`

### Custom Configuration

If you need custom redirect URLs, you can override them:

```env
# Custom redirect URLs (optional)
SUPABASE_REDIRECT_URL=https://custom-domain.com/auth/callback
SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=https://custom-domain.com/auth/confirm
```

## 📧 How It Works

### Registration Process

1. User registers with email and password
2. Supabase sends confirmation email with redirect URL
3. User clicks link in email
4. User is redirected to `/auth/confirm` with tokens in URL hash
5. JavaScript processes tokens and sends to backend
6. User is logged in and redirected to dashboard

### Email Template

The confirmation email will now redirect to your application URL instead of `localhost:3000`.

### Supabase Dashboard Configuration

**Important**: You may also need to update your Supabase project settings:

1. Go to your Supabase dashboard
2. Navigate to **Authentication** → **Settings** → **URL Configuration**
3. Add your domains to **Redirect URLs**:
   - `http://localhost:8000/auth/confirm`
   - `http://localhost:8080/auth/confirm`
   - `https://yourdomain.com/auth/confirm` (for production)

## 🔄 Routes

The application includes these authentication routes:

- `GET /auth/confirm` - Email confirmation page
- `POST /auth/confirm` - Process confirmation tokens (AJAX)
- `GET /auth/callback` - General auth callback (fallback)

## 🌐 Multi-Environment Support

The configuration automatically adapts to different environments:

| Environment | APP_URL | Redirect URL |
|-------------|---------|--------------|
| Local Development | `http://localhost:8000` | `http://localhost:8000/auth/confirm` |
| Docker | `http://localhost:8080` | `http://localhost:8080/auth/confirm` |
| Production | `https://yourdomain.com` | `https://yourdomain.com/auth/confirm` |

## 🚀 Deployment

For Docker deployment, the environment variables are automatically configured in `docker-compose.yml`.

For production deployment, make sure to:

1. Set correct `APP_URL` in your production environment
2. Add your production domain to Supabase redirect URLs
3. Ensure HTTPS is configured for production

## 🛠️ Troubleshooting

### Common Issues

1. **Still redirecting to localhost:3000**
   - Check Supabase dashboard redirect URL settings
   - Verify environment variables are loaded
   - Clear browser cache

2. **Confirmation fails**
   - Check browser console for JavaScript errors
   - Verify CSRF token is valid
   - Check Laravel logs for errors

3. **Tokens not processed**
   - Ensure JavaScript is enabled
   - Check network tab for failed requests
   - Verify route is accessible

### Debug Steps

1. Check environment variables:
```bash
docker exec lab-inventory php artisan about
```

2. Check current configuration:
```bash
# In Laravel app
echo env('SUPABASE_EMAIL_CONFIRM_REDIRECT_URL');
```

3. Test confirmation endpoint:
```bash
curl http://localhost:8080/auth/confirm
```

## 📱 Mobile Considerations

The confirmation flow works on mobile devices and will redirect to your application regardless of the email client used.

For mobile apps, you may want to implement deep linking using custom URL schemes in the future.
