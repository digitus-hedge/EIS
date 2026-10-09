<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New career application</title>
</head>
<body style="margin:0;padding:0;background:#f5f1e8;font-family:'Segoe UI','Helvetica Neue',Arial,sans-serif;color:#111111;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5f1e8;padding:28px 12px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;">
          <tr>
            <td style="background:#E8792D;padding:22px 28px;color:#ffffff;">
              <p style="margin:0 0 4px;font-size:13px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">Energy Inspection Services Ltd</p>
              <h1 style="margin:0;font-size:22px;line-height:1.3;font-weight:700;">New career application</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:26px 28px 8px;">
              <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#3d3d3d;">
                A new application was sent from the Career page of the website.
              </p>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;line-height:1.5;">
                <tr>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;color:#888888;width:38%;vertical-align:top;">Post</td>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;font-weight:700;">{{ $enquiry->apply_for ?: '-' }}</td>
                </tr>
                <tr>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;color:#888888;vertical-align:top;">Name</td>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;font-weight:700;">{{ $enquiry->name }}</td>
                </tr>
                <tr>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;color:#888888;vertical-align:top;">E-mail</td>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;">
                    <a href="mailto:{{ $enquiry->email }}" style="color:#C4611E;">{{ $enquiry->email }}</a>
                  </td>
                </tr>
                <tr>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;color:#888888;vertical-align:top;">Phone</td>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;">{{ $enquiry->phone ?: '-' }}</td>
                </tr>
                <tr>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;color:#888888;vertical-align:top;">Nationality</td>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;">{{ $enquiry->nationality ?: '-' }}</td>
                </tr>
                <tr>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;color:#888888;vertical-align:top;">Location</td>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;">{{ $enquiry->location ?: '-' }}</td>
                </tr>
                <tr>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;color:#888888;vertical-align:top;">Message</td>
                  <td style="padding:10px 0;border-bottom:1px solid #ececec;">{!! $enquiry->message ? nl2br(e($enquiry->message)) : '-' !!}</td>
                </tr>
                <tr>
                  <td style="padding:10px 0;color:#888888;vertical-align:top;">Received</td>
                  <td style="padding:10px 0;">{{ $enquiry->created_at?->format('d M Y, h:i A') ?? '-' }}</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:14px 28px 28px;">
              <p style="margin:0;font-size:13.5px;line-height:1.6;color:#666666;">
                The CV is attached to this email. You can also open it from Admin &rsaquo; Career &rsaquo; Enquiries.
                Reply to this email to write back to the applicant.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>