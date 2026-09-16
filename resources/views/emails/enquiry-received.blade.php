<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: 'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 8px 24px rgba(0,0,0,0.08);">

                    <tr>
                        <td style="background:linear-gradient(90deg, #E8792D 0%, #C4611E 100%); padding:28px 32px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:middle;">
                                        <p style="margin:0 0 4px; font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:rgba(255,255,255,0.85);">Website Enquiry</p>
                                        <h1 style="margin:0; font-size:22px; font-weight:800; color:#ffffff;">New Enquiry Received</h1>
                                    </td>
                                    <td style="vertical-align:middle; text-align:right; width:120px;">
                                        <img src="{{ $message->embed($logoPath) }}" alt="EIS Logo" style="max-height:40px; width:auto; display:inline-block;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:36px 32px 8px;">
                            <p style="font-size:14.5px; color:#555555; line-height:1.6; margin:0 0 28px;">
                                You have received a new enquiry from the Energy Inspection Services Ltd website contact form. Details are below.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; background:#faf7f4; border-radius:10px; overflow:hidden;">
                                <tr>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:12.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#C4611E; width:150px;">Full Name</td>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:14.5px; color:#1B2A2E;">{{ $enquiry->full_name ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:12.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#C4611E;">Email</td>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:14.5px;">
                                        <a href="mailto:{{ $enquiry->email }}" style="color:#E8792D; text-decoration:none; font-weight:600;">{{ $enquiry->email ?: '—' }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:12.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#C4611E;">Address</td>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:14.5px; color:#1B2A2E;">{{ $enquiry->address ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:12.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#C4611E;">Town/City</td>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:14.5px; color:#1B2A2E;">{{ $enquiry->town_city ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:12.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#C4611E;">Country</td>
                                    <td style="padding:14px 20px; border-bottom:1px solid #eeeeee; font-size:14.5px; color:#1B2A2E;">{{ $enquiry->country ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 20px; font-size:12.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#C4611E; vertical-align:top;">Comments</td>
                                    <td style="padding:14px 20px; font-size:14.5px; color:#1B2A2E; line-height:1.6; white-space:pre-wrap;">{{ $enquiry->comments ?: '—' }}</td>
                                </tr>
                            </table>

                            <p style="font-size:12.5px; color:#999999; margin:28px 0 8px;">
                                Received on {{ $enquiry->created_at->format('M d, Y \a\t h:i A') }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 32px;">
                            <div style="height:1px; background:#eeeeee;"></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color:#1B2A2E; padding:22px 32px; text-align:center;">
                            <p style="margin:0; font-size:12.5px; color:rgba(255,255,255,0.75);">
                                Energy Inspection Services Ltd. &mdash; Website Enquiry System
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>