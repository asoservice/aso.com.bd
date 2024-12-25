{{-- @component('mail::message')
            <h3 class="card-title">{{ @$content['title'][$locale] }}</h3>
            <p class="card-text">{!! @$emailContent !!}</p>
            @component('mail::button', ['url' => @$content['button_url'][$locale]])
                {{@$content['button_text'][$locale] }}
            @endcomponent  
@endcomponent --}}

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="  https://fixit.webiots.co.in/email/favicon.png" type="image/x-icon" />
  <link rel="shortcut icon" href="https://fixit.webiots.co.in/email/favicon.png" type="image/x-icon" />
  <title>{{ @$content['title'][$locale] }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&display=swap"
    rel="stylesheet" />
</head>

<body
  style="text-align: center; margin: 20px auto; width: 650px; font-family: 'DM Sans', sans-serif; background-color: #e2e2e2; display: block; position: relative">
  <table style="border-collapse: collapse; border-spacing: 0; background-color: #fff; width: 100%" text-align="center"
    >
    <tbody>
      <tr>
        <td>
          <table style="border-collapse: collapse; border-spacing: 0" text-align="center" width="100%">
            <tr class="header">
              <td text-align="center" valign="top"
                style="padding: 16px 26px">
                <img src="https://fixit.webiots.co.in/email/logo.png" class="main-logo" style="width: 100px; height: auto" />
              </td>

            </tr>
          </table>
        </td>
      </tr>

      <tr>
        <td class="banner" style="position: relative; padding-inline: 25px">
          <table style="border-collapse: collapse; border-spacing: 0; width: 100%">
            <tr>
              <td colspan="2"><img style="width: 100%; height: auto" src="https://fixit.webiots.co.in/email/banner.jpg" alt="banner" />
              </td>
            </tr>
          </table>
        </td>
      </tr>

      {!! @$emailContent !!}
      <tr>
        <td class="section-t"
          style="margin-top: 32px; display: block">
          <a style="text-decoration: none; font-weight: bold; font-size: 20px; line-height: 26px; display: inline-block; color: #ffffff; background: #5465FF; border-radius: 6px; padding: 14px 44px"
            href="{{ @$content['button_url'][$locale] }}" class="button-solid">{{ @$content['button_text'][$locale] }}</a>
        </td>
      </tr>

      <tr>
        <td class="section-t"
          style="background-color: #212121; padding: 26px 0; margin-top: 32px; display: block">
          <table
            style="border-collapse: collapse; border-spacing: 0; position: relative; width: 100%; vertical-align: middle; margin: 0 auto; width: 326px"
            class="footer">
            <tr>
              <td class="footer-content">
                <table style="border-collapse: collapse; border-spacing: 0" class="footer-social-icon" text-align="center" class="text-center"
                  style="vertical-align: middle; margin: 0 auto; width: 326px">
                  <tr class="social">
                    <td style="width: 20px; height:20px; display: inline-block; margin: 0 10px">
                      <a style="text-decoration: none" href="#"><img style="width: 100%" src="https://fixit.webiots.co.in/email/fb.png"
                          alt="fb" /></a>
                    </td>
                    <td style="width: 20px; height:20px; display: inline-block; margin: 0 10px">
                      <a style="text-decoration: none" href="#"><img style="width: 100%" src="https://fixit.webiots.co.in/email/insta.png"
                          alt="insta" /></a>
                    </td>
                    <td style="width: 20px; height:20px; display: inline-block; margin: 0 10px">
                      <a style="text-decoration: none" href="#"><img style="width: 100%" src="https://fixit.webiots.co.in/email/twitter-w.png"
                          alt="twitter" /></a>
                    </td>
                    <td style="width: 20px; height:20px; display: inline-block; margin: 0 10px">
                      <a style="text-decoration: none" href="#"><img style="width: 100%" src="https://fixit.webiots.co.in/email/pinterest-w.png"
                          alt="pinterest" /></a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <p
                        style="font-weight: 800; font-size: 12px; line-height: 23px; text-align: center; letter-spacing: 0.5px; color: #e4e4e4; margin-top: 15px; text-transform: uppercase">
                        THIS EMAIL WAS CREATED USING THE fixit. MADE WITH BY DESIGN fixit TEAM.</p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>