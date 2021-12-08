<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <base href="">
    <meta charset="utf-8" />
    <title>ScheduleDown</title>
   <style>
   .page-break {
      page-break-after: always;
   }
   </style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="bg-white">
    <div class="">
      @if(!empty($VoucherSold))
         <div style="margin:auto;position: relative;display: -webkit-box;display: -ms-flexbox;display: flex;text-align: center;justify-content: center;-webkit-box-orient: vertical;-webkit-box-direction: normal;-ms-flex-direction: column;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: rgb(255, 255, 255);background-clip: border-box;border: 1px solid #d1d1d4;border-radius: 0.42rem;width: 100%; max-width: 450px;">
         @foreach($VoucherSold as $VoucherSoldData)
            <div style="font-size:17px;line-height:1em;font-family:'Helvetica';color:#000;margin:0;padding:0" bgcolor="#ffffff">
               <table align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;width:100%;max-width:450px;text-align:center;margin:0 auto;padding:0;border:0" valign="middle" width="100%">
                  <tbody> 
                     <tr>
                        <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 24px;border:0;" valign="middle">
                           <p style="display:block;color:#000;font-size:1.47em;line-height:1.4em;margin:0">
                              {{ ($RecipientPersonalizedEmail) ? $RecipientPersonalizedEmail : (($VoucherSoldData['name']) ? $VoucherSoldData['name'] : '') }}
                           </p>
                        </td>
                     </tr> 
                     <tr>
                        <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 4px;border:0;" valign="middle">
                           <p style="display:block;color:#000;font-size:0.9em;line-height:1.4em;margin:0">
                              Voucher for
                           </p>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 24px;border:0;" valign="middle">
                           <p style="display:block;color:#000;font-size:1.47em;line-height:1.4em;margin:0">
                              {{ ($RecipientFirstName) ? $RecipientFirstName : '' }} {{ ($RecipientLastName) ? $RecipientLastName : '' }}
                           </p>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';color:#ffffff;margin:0;padding:0;border:0" valign="middle" bgcolor="#ffffff">
                           <div style="width: 100%;border-radius:8px;background:#ffffff; border: 4px solid #6c4bf6">
                              <table align="center" style="font-size:1em;vertical-align:middle;text-align:center;width:100%;margin:0;padding:0;border:0;border-collapse: collapse;border-radius: 1em;" valign="middle" width="100%">
                                 <tbody>
                                    <tr>
                                       <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:32px 0 0;border:0" valign="middle">
                                          <div style="width: 82px; height: 82px; min-width: 82px; min-height: 82px; font-size: 28px; border: 1px solid #e8e8ee; margin: 0 auto; border-radius: 0.42rem">
                                             <img alt="voucher-thumb" src="{{ asset('./assets/images/thumb.jpg') }}" width="80px" height="80px" style="border-radius: 0.42rem;margin-bottom: 10px;">
                                          </div>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:16px 32px 10px;border:0" valign="middle">
                                          <p style="display:block;font-size:1.17em;line-height:1.25em;color:#6c4bf6;font-weight:bold;margin:0">
                                             {{ ($VoucherSoldData['location_name']) ? $VoucherSoldData['location_name'] : '' }}
                                          </p>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;margin:0;padding:0 32px 32px;border:0" valign="middle">
                                          <p style="display:block;margin:0">
                                             <a style="text-decoration:none;font-size:0.88em;line-height:1.4em;color:#000">
                                             {{ ($VoucherSoldData['location_address']) ? $VoucherSoldData['location_address'] : '' }}
                                             </a>
                                          </p>
                                       </td>
                                    </tr>

                                    <tr>
                                       <td align="" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0;border:0" valign="middle">
                                          <table align="" style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;text-align:center;width:100%;margin:0;padding:0;border:0" valign="middle" width="100%">
                                             <tbody>
                                                <tr>  
                                                   <td align="left" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 6px 0 0;border:0;position: relative; background: #ffffff" valign="middle">
                                                      <div style="margin:0;padding:0;font-size:1em;font-family:'Helvetica';height:56px;width:28px; background: #ffffff; border-top-right-radius: 28px; border-bottom-right-radius: 28px; border: 4px solid #6c4bf6; border-left: none; margin-left: -4.5px"></div> 
                                                   </td>

                                                   <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';border-top-color:#6c4bf6;border-top-style:solid;border-bottom-color:#6c4bf6;border-bottom-style:solid;margin:0;padding:0;border-width:1px 0" valign="middle">
                                                      <table align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;vertical-align:middle;text-align:center;width:100%;margin:0;padding:0;border:0" valign="middle" width="100%">
                                                         <tbody>
                                                            <tr>
                                                               <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;border:0" valign="middle">
                                                                  <p style="display:block;font-size:0.88em;line-height:1.4em;color:#000;margin:0; padding:32px 16px 6px;">
                                                                     Voucher value
                                                                  </p>
                                                                  <p style="display:block;font-size:1.65em;line-height:1.25em;color:#6c4bf6;font-weight:bold;margin:0;padding:0 16px 32px;">
                                                                     CA $<span id="vaoucher-price">{{ ($VoucherSoldData['price']) ? $VoucherSoldData['price'] : 0 }}</span>
                                                                  </p>
                                                               </td>
                                                            </tr>
                                                            <tr>
                                                               <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;border:0" valign="middle">
                                                                  
                                                               </td>
                                                            </tr>
                                                         </tbody>
                                                      </table>
                                                   </td>
                                                   <td align="right" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 4px 0 0;border:0; position: relative; background: #ffffff" valign="middle">
                                                      <div style="margin:0;padding:0;font-size:1em;font-family:'Helvetica';height:56px;width:28px; background: #ffffff; border-top-left-radius: 28px; border-bottom-left-radius: 28px; border: 4px solid #6c4bf6; border-right: none; margin-right: -2.99px"></div>
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>

                                    <tr>
                                       <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:24px 32px 0;border:0" valign="middle">
                                          <p style="display:block;font-size:0.88em;line-height:1.4em;color:#6c4bf6;margin:0 0 8px">
                                             Voucher Code : <span style="font-weight:bolder">{{ ($VoucherSoldData['voucher_code']) ? $VoucherSoldData['voucher_code'] : '' }}</span>
                                          </p>
                                       </td>
                                    </tr> 
                                    <tr>
                                       <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 2px;border:0" valign="middle">
                                          <p style="display:block;font-size:0.88em;line-height:1.4em;color:#000;margin:0">
                                             Redeem on <span class="font-weight-bolder cursor-pointer">all services</span> <i class="fa fa-chevron-right icon-sm"></i>
                                          </p>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 2px;border:0" valign="middle">
                                          <p style="display:block;font-size:0.88em;line-height:1.4em;color:#000;margin:0">
                                             Valid until {{ ($VoucherSoldData['end_date']) ? date("d M Y",strtotime($VoucherSoldData['end_date'])) : '' }}
                                          </p>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td align="center" style="border-collapse:collapse;border-spacing:0;font-size:1em;font-family:'Helvetica';margin:0;padding:0 32px 32px;border:0" valign="middle">
                                          <p style="display:block;font-size:0.88em;line-height:1.4em;color:#000;margin:0">
                                             For multiple-use
                                          </p>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <td style="height:24px"></td>
                     </tr>
                  </tbody>
               </table> 
            </div> 
            <div class="page-break"></div>
         @endforeach
         </div>
      @endif
    </div>
</body>

</html>