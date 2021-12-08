<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
   <base href="">
   <meta charset="utf-8" />
   <title>ScheduleDown </title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />

   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="bg-white">
   <div style="font-size:17px;line-height:1em;font-family:'Helvetica';color:#000;margin:0;padding:0" bgcolor="#ffffff">
      <center>
         <table align="center"
            style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;width:100%;max-width:450px;text-align:center;margin:0 auto;padding:0;border:0"
            valign="middle" width="100%">
            <tbody>
               <tr>
                  <td align="centerpx"
                     style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:32px 32px 24px;border:0"
                     valign="middle">
                     <p style="display:block;color:#000;font-size:1em;line-height:1.6em;margin:0">{{
                        ($recipient_personalized_email) ? $recipient_personalized_email : (($voucher_sold_data['name'])
                        ? $voucher_sold_data['name'] : '') }}
                     </p>
                  </td>
               </tr>
               <tr>
                  <td align="centerpx"
                     style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 4px;border:0"
                     valign="middle">
                     <p style="display:block;color:#000;font-size:0.9em;line-height:1.4em;margin:0">
                        Voucher for
                     </p>
                  </td>
               </tr>
               <tr>
                  <td align="centerpx"
                     style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 24px;border:0"
                     valign="middle">
                     <p style="display:block;color:#000;font-size:1.47em;line-height:1.4em;margin:0">{{
                        ($recipient_first_name) ? $recipient_first_name : '' }} {{ ($recipient_last_name) ?
                        $recipient_last_name : '' }}</p>
                  </td>
               </tr>
               <tr>
                  <td align="center"
                     style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';border-radius:8px;background-image:linear-gradient(-45deg,rgb(190,74,244) 0%,rgb(92,55,246) 100%);color:#fff;margin:0;padding:0;border:0"
                     valign="middle" bgcolor="#6c4bf6">
                     <table align="center"
                        style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;text-align:center;width:100%;margin:0;padding:0;border:0"
                        valign="middle" width="100%">
                        <tbody>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:32px 0 0;border:0"
                                 valign="middle">
                                 <img height="80" src="{{ asset('./assets/images/thumb.jpg') }}"
                                    style="max-width:100%;line-height:100%;outline:none;text-decoration:none;height:80px;width:80px;border-radius:8px;border:1px solid #fff"
                                    width="80" class="CToWUd">
                              </td>
                           </tr>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:16px 32px 10px;border:0"
                                 valign="middle">
                                 <p
                                    style="display:block;font-size:1.17em;line-height:1.25em;color:#ffffff;font-weight:bold;margin:0">
                                    {{ ($location_info['location_name']) ? $location_info['location_name'] : '' }}
                                 </p>
                              </td>
                           </tr>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 32px;border:0"
                                 valign="middle">
                                 <p style="display:block;margin:0">
                                    <a style="text-decoration:none;font-size:0.88em;line-height:1.4em;color:#ffffff">
                                       {{ ($location_info['location_address']) ? $location_info['location_address'] : ''
                                       }}
                                    </a>
                                 </p>
                              </td>
                           </tr>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0;border:0"
                                 valign="middle">
                                 <table align="center"
                                    style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;text-align:center;width:100%;margin:0;padding:0;border:0"
                                    valign="middle" width="100%">
                                    <tbody>
                                       <tr>
                                          <td align="center"
                                             style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';width:28;margin:0;padding:0 8px 0 0;border:0"
                                             valign="middle" width="28">
                                             <table align="center"
                                                style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;text-align:center;width:100%;margin:0;padding:0;border:0"
                                                valign="middle" width="100%">
                                                <tbody>
                                                   <tr>
                                                      <td align="center" height="56"
                                                         style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';border-top-right-radius:28px;border-bottom-right-radius:28px;height:56px;width:28px;margin:0;padding:0;border:0"
                                                         valign="middle" width="28" bgcolor="#ffffff"></td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                          <td align="center"
                                             style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';border-top-color:#ffffff;border-top-style:solid;border-bottom-color:#ffffff;border-bottom-style:solid;margin:0;padding:0;border-width:1px 0"
                                             valign="middle">
                                             <table align="center"
                                                style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;text-align:center;width:100%;margin:0;padding:0;border:0"
                                                valign="middle" width="100%">
                                                <tbody>
                                                   <tr>
                                                      <td align="centerpx"
                                                         style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:32px 16px 6px;border:0"
                                                         valign="middle">
                                                         <p
                                                            style="display:block;font-size:0.88em;line-height:1.4em;color:#ffffff;margin:0">
                                                            Voucher value
                                                         </p>
                                                      </td>
                                                   </tr>
                                                   <tr>
                                                      <td align="centerpx"
                                                         style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 16px 32px;border:0"
                                                         valign="middle">
                                                         <p
                                                            style="display:block;font-size:1.65em;line-height:1.25em;color:#ffffff;font-weight:bold;margin:0">
                                                            CA $<span id="vaoucher-price">{{
                                                               ($voucher_sold_data['price']) ?
                                                               $voucher_sold_data['price'] : 0 }}</span>
                                                         </p>
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                          <td align="center"
                                             style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';width:28;margin:0;padding:0 0 0 8px;border:0"
                                             valign="middle" width="28">
                                             <table align="center"
                                                style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;text-align:center;width:100%;margin:0;padding:0;border:0"
                                                valign="middle" width="100%">
                                                <tbody>
                                                   <tr>
                                                      <td align="center" height="56"
                                                         style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';border-top-left-radius:28px;border-bottom-left-radius:28px;height:56px;width:28px;margin:0;padding:0;border:0"
                                                         valign="middle" width="28" bgcolor="#ffffff"></td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:24px 32px 0;border:0"
                                 valign="middle">
                                 <p style="display:block;font-size:0.88em;line-height:1.4em;color:#ffffff;margin:0">
                                    Voucher Code : <span style="font-weight:bolder">{{
                                       ($voucher_sold_data['voucher_code']) ? $voucher_sold_data['voucher_code'] : ''
                                       }}</span>
                                 </p>
                              </td>
                           </tr>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:24px 32px;border:0"
                                 valign="middle">
                                 <a href="#"
                                    style="text-decoration:none;background-color:#ffffff;border-radius:4px;height:48px;font-size:1.17em;line-height:48px;color:#000000;display:inline-block;padding:0 60px;border:1px solid rgb(213,215,218)"
                                    target="_blank">
                                    Book now
                                 </a>
                              </td>
                           </tr>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 2px;border:0"
                                 valign="middle">
                                 <p style="display:block;font-size:0.88em;line-height:1.4em;color:#ffffff;margin:0">
                                    Redeem on <span class="font-weight-bolder cursor-pointer">all services</span> <i
                                       class="fa fa-chevron-right icon-sm"></i>
                                 </p>
                              </td>
                           </tr>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 2px;border:0"
                                 valign="middle">
                                 <p style="display:block;font-size:0.88em;line-height:1.4em;color:#ffffff;margin:0">
                                    Valid until {{ ($voucher_sold_data['end_date']) ? date("d M
                                    Y",strtotime($voucher_sold_data['end_date'])) : '' }}
                                 </p>
                              </td>
                           </tr>
                           <tr>
                              <td align="center"
                                 style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 32px;border:0"
                                 valign="middle">
                                 <p style="display:block;font-size:0.88em;line-height:1.4em;color:#ffffff;margin:0">
                                    For multiple-use
                                 </p>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td style="height:24px"></td>
               </tr>
            </tbody>
         </table>
      </center>
   </div>
</body>

</html>