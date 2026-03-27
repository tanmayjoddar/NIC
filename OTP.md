# OTP Email Verification After Login

After a user signs in with correct credentials, instead of going straight to `/form`, generate a 6-digit OTP, store it in a new `otp_tokens` table (with expiry), email it to the user, and redirect them to an OTP verification page. Only after entering the correct OTP are they redirected to `/form`.

## Proposed Changes

---

### Migration – new `otp_tokens` table

#### [NEW] `create_otp_tokens_table.php` (in `database/migrations/`)

- Columns: `id`, `email` (FK concept, string), `otp` (6-char string), `expires_at` (timestamp), `timestamps()`
- OTP expires after **10 minutes**

---

### Model

#### [NEW] `OtpToken.php` (in `app/Models/`)

- `$fillable`: `email`, `otp`, `expires_at`
- No extra casts needed

---

### Controller

#### [NEW] `OtpController.php` (in `app/Http/Controllers/`)

Three methods:

| Method               | Route           | What it does                                                                                  |
| -------------------- | --------------- | --------------------------------------------------------------------------------------------- |
| `showOtpPage()`      | `GET /otp`      | Returns `otp` view; guards if no pending email in session                                     |
| `sendOtp($email)`    | internal helper | Deletes old OTPs for email, generates 6-digit OTP, stores in DB, sends mail via `Mail::raw()` |
| `verifyOtp(Request)` | `POST /otp`     | Validates OTP from DB, checks expiry, sets `session(['user'=>...])`, redirects to `/form`     |

#### [MODIFY] [AuthController.php](file:///d:/NIC/mahapp/app/Http/Controllers/AuthController.php)

[signinSubmit](file:///d:/NIC/mahapp/app/Http/Controllers/AuthController.php#40-57): After credential check passes, **instead of** `session(['user'=>$user])` + redirect to `/form`, call the internal `sendOtp` helper (or replicate logic inline), store `otp_email` in session, redirect to `GET /otp`.

---

### View

#### [NEW] `otp.blade.php` (in `resources/views/`)

- Extends `layouts.app`
- Single number input (6-digit), CSRF, POST to `/otp`
- Shows success/error flash messages

---

### Routes

#### [MODIFY] [web.php](file:///d:/NIC/mahapp/routes/web.php)

Add:

```php
use App\Http\Controllers\OtpController;

Route::get('/otp',  [OtpController::class, 'showOtpPage']);
Route::post('/otp', [OtpController::class, 'verifyOtp']);
```

---

### Mail Configuration

> [!IMPORTANT]
> You need to configure your `.env` for mail. Since there is no `.env` file visible (likely gitignored), add/update these keys:
>
> ```env
> MAIL_MAILER=smtp
> MAIL_HOST=smtp.gmail.com
> MAIL_PORT=587
> MAIL_USERNAME=your@gmail.com
> MAIL_PASSWORD=your_app_password
> MAIL_ENCRYPTION=tls
> MAIL_FROM_ADDRESS=your@gmail.com
> MAIL_FROM_NAME="NIC Project"
> ```
>
> For Gmail, use an **App Password** (not your normal password). Go to Google Account → Security → 2-Step Verification → App Passwords.

---

## Verification Plan

### Automated

No existing feature tests cover auth flows. No new automated tests are added (to keep scope minimal).

### Manual Verification Steps

1. Run migration:

   ```
   php artisan migrate
   ```

   Expected: new `otp_tokens` table created without error.

2. Start the dev server:

   ```
   php artisan serve
   ```

3. Open `http://127.0.0.1:8000/signup` → create a new account with a **real email address** you can check.

4. Go to `http://127.0.0.1:8000/signin` → sign in with those credentials.

5. **Expected**: Redirected to `http://127.0.0.1:8000/otp` (not `/form`).

6. Check your inbox for an email from "NIC Project" with the 6-digit OTP.

7. Enter the correct OTP → **Expected**: Redirected to `/form`.

8. Repeat step 4–6 but enter a **wrong OTP** → **Expected**: Error message, stays on `/otp`.

9. Repeat and wait 11+ minutes before submitting → **Expected**: "OTP expired" error.
