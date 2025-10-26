# ToyyibPay Integration Guide

## Problem Fixed ✅

**Issue:** After successful payment, ToyyibPay was redirecting users to `https://toyyibpay.com/main/` instead of back to your application.

**Root Cause:** The `billReturnUrl` parameter was set to a relative path (`'index.php'`) instead of an absolute URL.

**Solution:** Updated all ToyyibPay integration files to use **absolute URLs** with proper return and callback URLs.

---

## Files Updated

### 1. Payment Integration Files
- ✅ `getBookingInsertion.php`
- ✅ `admin/getBookingInsertion.php`
- ✅ `admin/getBookingInsertion copy.php`
- ✅ `staff/getBookingInsertion.php`
- ✅ `bookingtestold.php`

### 2. New Payment Handling Files
- ✅ `payment-success.php` - Handles successful payment redirect
- ✅ `payment-callback.php` - Handles server-to-server callback from ToyyibPay

### 3. Updated Display Files
- ✅ `booking-record.php` - Added payment success/failure messages

---

## How It Works Now

### Payment Flow

```
1. User creates booking
   ↓
2. System generates ToyyibPay bill with absolute URLs
   ↓
3. User redirected to ToyyibPay payment page
   ↓
4. User completes payment
   ↓
5. ToyyibPay redirects to: payment-success.php?booking_id=XXX&status_id=1
   ↓
6. payment-success.php updates booking status
   ↓
7. User redirected to booking-record.php with success message
   ↓
8. ToyyibPay also sends server callback to payment-callback.php (backup)
```

### URL Structure

**Before (WRONG):**
```php
'billReturnUrl' => 'index.php'  // ❌ Relative path
```

**After (CORRECT):**
```php
'billReturnUrl' => 'http://localhost/kapasbeautyspa.com/payment-success.php?booking_id=123'  // ✅ Absolute URL
'billCallbackUrl' => 'http://localhost/kapasbeautyspa.com/payment-callback.php'  // ✅ Absolute URL
```

### Dynamic URL Generation

The URLs are generated dynamically to work on any server:

```php
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
            . "://" . $_SERVER['HTTP_HOST'] 
            . '/kapasbeautyspa.com';

'billReturnUrl' => $base_url . '/payment-success.php?booking_id=' . $booking_id
```

---

## Payment Status Codes

ToyyibPay returns these status codes:

| Status ID | Meaning | Action Taken |
|-----------|---------|--------------|
| `1` | **Successful** | Update booking to "Telah Bayar" |
| `2` | **Pending** | Show pending message |
| `3` | **Failed** | Show failure message |

---

## Testing

### Local Testing (XAMPP)

URLs will be generated as:
```
billReturnUrl: http://localhost/kapasbeautyspa.com/payment-success.php?booking_id=001
billCallbackUrl: http://localhost/kapasbeautyspa.com/payment-callback.php
```

### Production Testing (InfinityFree/Live Server)

URLs will be generated as:
```
billReturnUrl: https://yourdomain.com/payment-success.php?booking_id=001
billCallbackUrl: https://yourdomain.com/payment-callback.php
```

---

## Important Notes

### 1. Callback URL Accessibility
- `payment-callback.php` must be **publicly accessible** for ToyyibPay servers to reach it
- Test that the URL works: `https://yourdomain.com/payment-callback.php`

### 2. Payment Logs
- Callback creates `payment_logs.txt` for debugging
- Check this file if payments aren't updating correctly
- **Important:** Add to `.gitignore` to prevent committing sensitive data

### 3. Double-Update Protection
Both `payment-success.php` and `payment-callback.php` update the booking status:
- **Return URL** - For user experience (immediate feedback)
- **Callback URL** - For reliability (backup in case user closes browser)

### 4. Security Considerations
- Validate booking belongs to logged-in user (already implemented)
- Consider adding signature verification for callbacks
- Log all payment attempts for audit trail

---

## Troubleshooting

### Still redirecting to ToyyibPay homepage?

**Check:**
1. Are you using the UPDATED files with absolute URLs?
2. Is your server accessible from the internet? (for callbacks)
3. Check ToyyibPay dashboard logs for error messages

### Payment successful but status not updating?

**Check:**
1. Database connection in `payment-success.php`
2. Check `payment_logs.txt` file for callback data
3. Verify booking_id is being passed correctly
4. Check browser console for JavaScript errors

### Callback not receiving data?

**Check:**
1. Is `payment-callback.php` publicly accessible?
2. Check web server error logs
3. Verify ToyyibPay has correct callback URL in their system
4. Test callback URL manually with POST data

---

## Production Deployment

### Before Going Live:

1. ✅ Update `.env` with production ToyyibPay credentials
2. ✅ Test payment flow on staging server
3. ✅ Verify callback URL is publicly accessible
4. ✅ Add `payment_logs.txt` to `.gitignore`
5. ✅ Enable HTTPS (ToyyibPay recommends SSL)
6. ✅ Test both successful and failed payment scenarios

### Environment Variables

Make sure your `.env` file has:
```env
TOYYIBPAY_SECRET_KEY=your_production_secret_key
TOYYIBPAY_CATEGORY_CODE=your_production_category_code
```

---

## Support

**ToyyibPay Support:**
- Website: https://toyyibpay.com/main/
- Support Hours: Monday to Friday, 7am – 5pm
- Contact: Via Facebook & WhatsApp Messenger

**Documentation:**
- API References: https://toyyibpay.com/apireferences/

---

## Summary

✅ **Problem Fixed:** Payment now redirects back to your app
✅ **User Experience:** Clear success/failure messages
✅ **Reliability:** Dual update system (return + callback)
✅ **Production Ready:** Works on any server (localhost or live)

Your ToyyibPay integration is now properly configured! 🎉

