@extends('layouts.app') 
@yield('innercss')
<style type="text/css">
	span.select2-selection.select2-selection--single {
	    background-color: #F3F6F9;
	    border-color: #F3F6F9;
	    color: #3F4254;
	    -webkit-transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
	    transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
	    transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
	    transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, -webkit-box-shadow 0.15s ease;
	    height: auto;
	    border-radius: 0.85rem !important;
	}
	span.select2-selection__rendered {
	    padding-bottom: 1.75rem !important;
	    padding-top: 1.75rem !important;
	    padding-left: 1.5rem !important;
	    padding-right: 1.5rem !important;
	}
	.select2-container--default .select2-selection--single .select2-selection__rendered{
		font-family: Poppins, Helvetica, "sans-serif";
	    color: #3F4254;
	    font-size: 16px;
	    font-weight: 400;
	    line-height: 1.5;
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow{
		top: 50% !important; 
	    transform: translateY(-50%);
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow:after, .select2-container--default .select2-selection--multiple .select2-selection__arrow:after{
		font-size: 0.85rem;
    	color: #3F4254;
	}
	.width-input {
		width: 100%;
	}
</style>
@section('content')
	<div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid">
		<!--begin::Content-->
		<div class="login-container order-2 order-lg-1 d-flex flex-center flex-row-fluid px-7 pt-lg-0 pb-lg-0 pt-4 pb-6 bg-white">
			<!--begin::Wrapper-->
			<div class="login-content d-flex flex-column pt-lg-0 pt-12">
				<!--begin::Logo-->
				<a href="#" class="login-logo pb-xl-20 pb-15">
					<img src="{{ asset('media/logos/logo-4.png') }}" class="max-h-70px" alt="" />
				</a>
				<!--end::Logo-->
				<!--begin::Signin-->
				<div class="login-form">
					<!--begin::Form-->
					<form method="POST" class="form" novalidate="novalidate" id="kt_login_signup_form" action="{{ route('register') }}">
						@csrf
						<input type="hidden" id="redirectURL" value="{{ route('home') }}">
						<div class="pb-5 pb-lg-15">
							<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Create Account</h3>
							<div class="text-muted font-weight-bold font-size-h4">Already have an Account ?
							<a href="{{ route('login') }}" class="text-primary font-weight-bolder">Sign In</a></div>
						</div>
						<!--begin::Title-->
						<!--begin::Form group-->
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">First Name</label>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="text" name="first_name" value="{{ old('first_name') }}" autocomplete="off" />
						</div>
						<!--begin::Form group-->
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Last Name</label>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="text" name="last_name" value="{{ old('last_name') }}" autocomplete="off" />
						</div>
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Company Name</label>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="text" name="company_name" value="{{ old('company_name') }}" autocomplete="off" />
						</div>
						<div class="form-group">
							<div style="display: none;" id="map"></div>
							<label class="font-size-h6 font-weight-bolder text-dark">Address</label>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="text" id="address" name="address" value="{{ old('address') }}" autocomplete="off" />
							<input type="hidden" name="loc_address" class="loc_address" value="">
							<input type="hidden" name="loc_apt" class="loc_apt" value="">
							<input type="hidden" name="loc_district" class="loc_district" value="">
							<input type="hidden" name="loc_city" class="loc_city" value="">
							<input type="hidden" name="loc_region" class="loc_region" value="">
							<input type="hidden" name="loc_county" class="loc_county" value="">
							<input type="hidden" name="loc_postcode" class="loc_postcode" value="">
							<input type="hidden" name="loc_country" class="loc_country" value="">
							<input type="hidden" name="lat" id="lat" value="">
							<input type="hidden" name="lng" id="lng" value="">
						</div>
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Phone number</label>
							<select class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0 mb-2 width-input" name="country_code" id="country_code">
								<option value="1" data-country="US" selected >United States 🇺🇸 +1</option>
								<option value="44" data-country="GB">United Kingdom 🇬🇧 +44</option>
								<option value="1" data-country="CA">Canada 🇨🇦 +1</option>
								<option value="971" data-country="AE">United Arab Emirates 🇦🇪 +971</option>
								<option value="93" data-country="AF">Afghanistan 🇦🇫 +93</option>
								<option value="358" data-country="AX">Åland Islands 🇦🇽 +358</option>
								<option value="355" data-country="AL">Albania 🇦🇱 +355</option>
								<option value="213" data-country="DZ">Algeria 🇩🇿 +213</option>
								<option value="1684" data-country="AS">American Samoa 🇦🇸 +1 684</option>
								<option value="376" data-country="AD">Andorra 🇦🇩 +376</option>
								<option value="244" data-country="AO">Angola 🇦🇴 +244</option>
								<option value="1264" data-country="AI">Anguilla 🇦🇮 +1 264</option>
								<option value="672" data-country="AQ">Antarctica 🇦🇶 +672</option>
								<option value="1268" data-country="AG">Antigua And Barbuda 🇦🇬 +1 268</option>
								<option value="54" data-country="AR">Argentina 🇦🇷 +54</option>
								<option value="374" data-country="AM">Armenia 🇦🇲 +374</option>
								<option value="297" data-country="AW">Aruba 🇦🇼 +297</option>
								<option value="247" data-country="AC">Ascension Island  +247</option>
								<option value="61" data-country="AU">Australia 🇦🇺 +61</option>
								<option value="43" data-country="AT">Austria 🇦🇹 +43</option>
								<option value="994" data-country="AZ">Azerbaijan 🇦🇿 +994</option>
								<option value="1242" data-country="BS">Bahamas 🇧🇸 +1 242</option>
								<option value="973" data-country="BH">Bahrain 🇧🇭 +973</option>
								<option value="880" data-country="BD">Bangladesh 🇧🇩 +880</option>
								<option value="1246" data-country="BB">Barbados 🇧🇧 +1 246</option>
								<option value="375" data-country="BY">Belarus 🇧🇾 +375</option>
								<option value="32" data-country="BE">Belgium 🇧🇪 +32</option>
								<option value="501" data-country="BZ">Belize 🇧🇿 +501</option>
								<option value="229" data-country="BJ">Benin 🇧🇯 +229</option>
								<option value="1441" data-country="BM">Bermuda 🇧🇲 +1 441</option>
								<option value="975" data-country="BT">Bhutan 🇧🇹 +975</option>
								<option value="591" data-country="BO">Bolivia, Plurinational State Of 🇧🇴 +591</option>
								<option value="599" data-country="BQ">Bonaire, Saint Eustatius And Saba 🇧🇶 +599</option>
								<option value="387" data-country="BA">Bosnia &amp; Herzegovina 🇧🇦 +387</option>
								<option value="267" data-country="BW">Botswana 🇧🇼 +267</option>
								<option value="55" data-country="BR">Brazil 🇧🇷 +55</option>
								<option value="246" data-country="IO">British Indian Ocean Territory 🇮🇴 +246</option>
								<option value="673" data-country="BN">Brunei Darussalam 🇧🇳 +673</option>
								<option value="359" data-country="BG">Bulgaria 🇧🇬 +359</option>
								<option value="226" data-country="BF">Burkina Faso 🇧🇫 +226</option>
								<option value="257" data-country="BI">Burundi 🇧🇮 +257</option>
								<option value="238" data-country="CV">Cabo Verde 🇨🇻 +238</option>
								<option value="855" data-country="KH">Cambodia 🇰🇭 +855</option>
								<option value="237" data-country="CM">Cameroon 🇨🇲 +237</option>
								<option value="1345" data-country="KY">Cayman Islands 🇰🇾 +1 345</option>
								<option value="236" data-country="CF">Central African Republic 🇨🇫 +236</option>
								<option value="235" data-country="TD">Chad 🇹🇩 +235</option>
								<option value="56" data-country="CL">Chile 🇨🇱 +56</option>
								<option value="86" data-country="CN">China 🇨🇳 +86</option>
								<option value="61" data-country="CX">Christmas Island 🇨🇽 +61</option>
								<option value="61" data-country="CC">Cocos (Keeling) Islands 🇨🇨 +61</option>
								<option value="57" data-country="CO">Colombia 🇨🇴 +57</option>
								<option value="269" data-country="KM">Comoros 🇰🇲 +269</option>
								<option value="682" data-country="CK">Cook Islands 🇨🇰 +682</option>
								<option value="506" data-country="CR">Costa Rica 🇨🇷 +506</option>
								<option value="225" data-country="CI">Côte d'Ivoire 🇨🇮 +225</option>
								<option value="385" data-country="HR">Croatia 🇭🇷 +385</option>
								<option value="53" data-country="CU">Cuba 🇨🇺 +53</option>
								<option value="599" data-country="CW">Curacao 🇨🇼 +599</option>
								<option value="357" data-country="CY">Cyprus 🇨🇾 +357</option>
								<option value="420" data-country="CZ">Czech Republic 🇨🇿 +420</option>
								<option value="243" data-country="CD">Democratic Republic Of Congo 🇨🇩 +243</option>
								<option value="45" data-country="DK">Denmark 🇩🇰 +45</option>
								<option value="253" data-country="DJ">Djibouti 🇩🇯 +253</option>
								<option value="1767" data-country="DM">Dominica 🇩🇲 +1 767</option>
								<option value="1809" data-country="DO">Dominican Republic 🇩🇴 +1 809</option>
								<option value="1829" data-country="DO">Dominican Republic 🇩🇴 +1 829</option>
								<option value="1849" data-country="DO">Dominican Republic 🇩🇴 +1 849</option>
								<option value="593" data-country="EC">Ecuador 🇪🇨 +593</option>
								<option value="20" data-country="EG">Egypt 🇪🇬 +20</option>
								<option value="503" data-country="SV">El Salvador 🇸🇻 +503</option>
								<option value="240" data-country="GQ">Equatorial Guinea 🇬🇶 +240</option>
								<option value="291" data-country="ER">Eritrea 🇪🇷 +291</option>
								<option value="372" data-country="EE">Estonia 🇪🇪 +372</option>
								<option value="251" data-country="ET">Ethiopia 🇪🇹 +251</option>
								<option value="388" data-country="EU">European Union 🇪🇺 +388</option>
								<option value="500" data-country="FK">Falkland Islands 🇫🇰 +500</option>
								<option value="298" data-country="FO">Faroe Islands 🇫🇴 +298</option>
								<option value="679" data-country="FJ">Fiji 🇫🇯 +679</option>
								<option value="358" data-country="FI">Finland 🇫🇮 +358</option>
								<option value="33" data-country="FR">France 🇫🇷 +33</option>
								<option value="241" data-country="FX">France, Metropolitan  +241</option>
								<option value="594" data-country="GF">French Guiana 🇬🇫 +594</option>
								<option value="689" data-country="PF">French Polynesia 🇵🇫 +689</option>
								<option value="241" data-country="GA">Gabon 🇬🇦 +241</option>
								<option value="220" data-country="GM">Gambia 🇬🇲 +220</option>
								<option value="995" data-country="GE">Georgia 🇬🇪 +995</option>
								<option value="49" data-country="DE">Germany 🇩🇪 +49</option>
								<option value="233" data-country="GH">Ghana 🇬🇭 +233</option>
								<option value="350" data-country="GI">Gibraltar 🇬🇮 +350</option>
								<option value="30" data-country="GR">Greece 🇬🇷 +30</option>
								<option value="299" data-country="GL">Greenland 🇬🇱 +299</option>
								<option value="473" data-country="GD">Grenada 🇬🇩 +473</option>
								<option value="590" data-country="GP">Guadeloupe 🇬🇵 +590</option>
								<option value="1671" data-country="GU">Guam 🇬🇺 +1 671</option>
								<option value="502" data-country="GT">Guatemala 🇬🇹 +502</option>
								<option value="44" data-country="GG">Guernsey 🇬🇬 +44</option>
								<option value="224" data-country="GN">Guinea 🇬🇳 +224</option>
								<option value="245" data-country="GW">Guinea-bissau 🇬🇼 +245</option>
								<option value="592" data-country="GY">Guyana 🇬🇾 +592</option>
								<option value="509" data-country="HT">Haiti 🇭🇹 +509</option>
								<option value="504" data-country="HN">Honduras 🇭🇳 +504</option>
								<option value="852" data-country="HK">Hong Kong 🇭🇰 +852</option>
								<option value="36" data-country="HU">Hungary 🇭🇺 +36</option>
								<option value="354" data-country="IS">Iceland 🇮🇸 +354</option>
								<option value="91" data-country="IN">India 🇮🇳 +91</option>
								<option value="62" data-country="ID">Indonesia 🇮🇩 +62</option>
								<option value="98" data-country="IR">Iran, Islamic Republic Of 🇮🇷 +98</option>
								<option value="964" data-country="IQ">Iraq 🇮🇶 +964</option>
								<option value="353" data-country="IE">Ireland 🇮🇪 +353</option>
								<option value="44" data-country="IM">Isle Of Man 🇮🇲 +44</option>
								<option value="972" data-country="IL">Israel 🇮🇱 +972</option>
								<option value="39" data-country="IT">Italy 🇮🇹 +39</option>
								<option value="1876" data-country="JM">Jamaica 🇯🇲 +1 876</option>
								<option value="81" data-country="JP">Japan 🇯🇵 +81</option>
								<option value="44" data-country="JE">Jersey 🇯🇪 +44</option>
								<option value="962" data-country="JO">Jordan 🇯🇴 +962</option>
								<option value="7" data-country="KZ">Kazakhstan 🇰🇿 +7</option>
								<option value="76" data-country="KZ">Kazakhstan 🇰🇿 +7 6</option>
								<option value="77" data-country="KZ">Kazakhstan 🇰🇿 +7 7</option>
								<option value="254" data-country="KE">Kenya 🇰🇪 +254</option>
								<option value="686" data-country="KI">Kiribati 🇰🇮 +686</option>
								<option value="850" data-country="KP">Korea, Democratic People's Republic Of 🇰🇵 +850</option>
								<option value="82" data-country="KR">Korea, Republic Of 🇰🇷 +82</option>
								<option value="383" data-country="XK">Kosovo  +383</option>
								<option value="965" data-country="KW">Kuwait 🇰🇼 +965</option>
								<option value="996" data-country="KG">Kyrgyzstan 🇰🇬 +996</option>
								<option value="856" data-country="LA">Lao People's Democratic Republic 🇱🇦 +856</option>
								<option value="371" data-country="LV">Latvia 🇱🇻 +371</option>
								<option value="961" data-country="LB">Lebanon 🇱🇧 +961</option>
								<option value="266" data-country="LS">Lesotho 🇱🇸 +266</option>
								<option value="231" data-country="LR">Liberia 🇱🇷 +231</option>
								<option value="218" data-country="LY">Libya 🇱🇾 +218</option>
								<option value="423" data-country="LI">Liechtenstein 🇱🇮 +423</option>
								<option value="370" data-country="LT">Lithuania 🇱🇹 +370</option>
								<option value="352" data-country="LU">Luxembourg 🇱🇺 +352</option>
								<option value="853" data-country="MO">Macao 🇲🇴 +853</option>
								<option value="389" data-country="MK">Macedonia, The Former Yugoslav Republic Of 🇲🇰 +389</option>
								<option value="261" data-country="MG">Madagascar 🇲🇬 +261</option>
								<option value="265" data-country="MW">Malawi 🇲🇼 +265</option>
								<option value="60" data-country="MY">Malaysia 🇲🇾 +60</option>
								<option value="960" data-country="MV">Maldives 🇲🇻 +960</option>
								<option value="223" data-country="ML">Mali 🇲🇱 +223</option>
								<option value="356" data-country="MT">Malta 🇲🇹 +356</option>
								<option value="692" data-country="MH">Marshall Islands 🇲🇭 +692</option>
								<option value="596" data-country="MQ">Martinique 🇲🇶 +596</option>
								<option value="222" data-country="MR">Mauritania 🇲🇷 +222</option>
								<option value="230" data-country="MU">Mauritius 🇲🇺 +230</option>
								<option value="262" data-country="YT">Mayotte 🇾🇹 +262</option>
								<option value="52" data-country="MX">Mexico 🇲🇽 +52</option>
								<option value="691" data-country="FM">Micronesia, Federated States Of 🇫🇲 +691</option>
								<option value="373" data-country="MD">Moldova 🇲🇩 +373</option>
								<option value="377" data-country="MC">Monaco 🇲🇨 +377</option>
								<option value="976" data-country="MN">Mongolia 🇲🇳 +976</option>
								<option value="382" data-country="ME">Montenegro 🇲🇪 +382</option>
								<option value="1664" data-country="MS">Montserrat 🇲🇸 +1 664</option>
								<option value="212" data-country="MA">Morocco 🇲🇦 +212</option>
								<option value="258" data-country="MZ">Mozambique 🇲🇿 +258</option>
								<option value="95" data-country="MM">Myanmar 🇲🇲 +95</option>
								<option value="264" data-country="NA">Namibia 🇳🇦 +264</option>
								<option value="674" data-country="NR">Nauru 🇳🇷 +674</option>
								<option value="977" data-country="NP">Nepal 🇳🇵 +977</option>
								<option value="31" data-country="NL">Netherlands 🇳🇱 +31</option>
								<option value="687" data-country="NC">New Caledonia 🇳🇨 +687</option>
								<option value="64" data-country="NZ">New Zealand 🇳🇿 +64</option>
								<option value="505" data-country="NI">Nicaragua 🇳🇮 +505</option>
								<option value="227" data-country="NE">Niger 🇳🇪 +227</option>
								<option value="234" data-country="NG">Nigeria 🇳🇬 +234</option>
								<option value="683" data-country="NU">Niue 🇳🇺 +683</option>
								<option value="672" data-country="NF">Norfolk Island 🇳🇫 +672</option>
								<option value="1670" data-country="MP">Northern Mariana Islands 🇲🇵 +1 670</option>
								<option value="47" data-country="NO">Norway 🇳🇴 +47</option>
								<option value="968" data-country="OM">Oman 🇴🇲 +968</option>
								<option value="92" data-country="PK">Pakistan 🇵🇰 +92</option>
								<option value="680" data-country="PW">Palau 🇵🇼 +680</option>
								<option value="970" data-country="PS">Palestinian Territory, Occupied 🇵🇸 +970</option>
								<option value="507" data-country="PA">Panama 🇵🇦 +507</option>
								<option value="675" data-country="PG">Papua New Guinea 🇵🇬 +675</option>
								<option value="595" data-country="PY">Paraguay 🇵🇾 +595</option>
								<option value="51" data-country="PE">Peru 🇵🇪 +51</option>
								<option value="63" data-country="PH">Philippines 🇵🇭 +63</option>
								<option value="872" data-country="PN">Pitcairn 🇵🇳 +872</option>
								<option value="48" data-country="PL">Poland 🇵🇱 +48</option>
								<option value="351" data-country="PT">Portugal 🇵🇹 +351</option>
								<option value="1787" data-country="PR">Puerto Rico 🇵🇷 +1 787</option>
								<option value="1939" data-country="PR">Puerto Rico 🇵🇷 +1 939</option>
								<option value="974" data-country="QA">Qatar 🇶🇦 +974</option>
								<option value="242" data-country="CG">Republic Of Congo 🇨🇬 +242</option>
								<option value="262" data-country="RE">Reunion 🇷🇪 +262</option>
								<option value="40" data-country="RO">Romania 🇷🇴 +40</option>
								<option value="7" data-country="RU">Russian Federation 🇷🇺 +7</option>
								<option value="73" data-country="RU">Russian Federation 🇷🇺 +7 3</option>
								<option value="74" data-country="RU">Russian Federation 🇷🇺 +7 4</option>
								<option value="78" data-country="RU">Russian Federation 🇷🇺 +7 8</option>
								<option value="250" data-country="RW">Rwanda 🇷🇼 +250</option>
								<option value="590" data-country="BL">Saint Barthélemy 🇧🇱 +590</option>
								<option value="290" data-country="SH">Saint Helena, Ascension And Tristan Da Cunha 🇸🇭 +290</option>
								<option value="1869" data-country="KN">Saint Kitts And Nevis 🇰🇳 +1 869</option>
								<option value="1758" data-country="LC">Saint Lucia 🇱🇨 +1 758</option>
								<option value="590" data-country="MF">Saint Martin 🇲🇫 +590</option>
								<option value="508" data-country="PM">Saint Pierre And Miquelon 🇵🇲 +508</option>
								<option value="1784" data-country="VC">Saint Vincent And The Grenadines 🇻🇨 +1 784</option>
								<option value="685" data-country="WS">Samoa 🇼🇸 +685</option>
								<option value="378" data-country="SM">San Marino 🇸🇲 +378</option>
								<option value="239" data-country="ST">Sao Tome and Principe 🇸🇹 +239</option>
								<option value="966" data-country="SA">Saudi Arabia 🇸🇦 +966</option>
								<option value="221" data-country="SN">Senegal 🇸🇳 +221</option>
								<option value="381" data-country="RS">Serbia 🇷🇸 +381</option>
								<option value="248" data-country="SC">Seychelles 🇸🇨 +248</option>
								<option value="232" data-country="SL">Sierra Leone 🇸🇱 +232</option>
								<option value="65" data-country="SG">Singapore 🇸🇬 +65</option>
								<option value="1721" data-country="SX">Sint Maarten 🇸🇽 +1 721</option>
								<option value="421" data-country="SK">Slovakia 🇸🇰 +421</option>
								<option value="386" data-country="SI">Slovenia 🇸🇮 +386</option>
								<option value="677" data-country="SB">Solomon Islands 🇸🇧 +677</option>
								<option value="252" data-country="SO">Somalia 🇸🇴 +252</option>
								<option value="27" data-country="ZA">South Africa 🇿🇦 +27</option>
								<option value="211" data-country="SS">South Sudan 🇸🇸 +211</option>
								<option value="34" data-country="ES">Spain 🇪🇸 +34</option>
								<option value="94" data-country="LK">Sri Lanka 🇱🇰 +94</option>
								<option value="249" data-country="SD">Sudan 🇸🇩 +249</option>
								<option value="597" data-country="SR">Suriname 🇸🇷 +597</option>
								<option value="47" data-country="SJ">Svalbard And Jan Mayen 🇸🇯 +47</option>
								<option value="268" data-country="SZ">Swaziland 🇸🇿 +268</option>
								<option value="46" data-country="SE">Sweden 🇸🇪 +46</option>
								<option value="41" data-country="CH">Switzerland 🇨🇭 +41</option>
								<option value="963" data-country="SY">Syrian Arab Republic 🇸🇾 +963</option>
								<option value="886" data-country="TW">Taiwan 🇹🇼 +886</option>
								<option value="992" data-country="TJ">Tajikistan 🇹🇯 +992</option>
								<option value="255" data-country="TZ">Tanzania, United Republic Of 🇹🇿 +255</option>
								<option value="66" data-country="TH">Thailand 🇹🇭 +66</option>
								<option value="670" data-country="TL">Timor-Leste, Democratic Republic of 🇹🇱 +670</option>
								<option value="228" data-country="TG">Togo 🇹🇬 +228</option>
								<option value="690" data-country="TK">Tokelau 🇹🇰 +690</option>
								<option value="676" data-country="TO">Tonga 🇹🇴 +676</option>
								<option value="1868" data-country="TT">Trinidad And Tobago 🇹🇹 +1 868</option>
								<option value="290" data-country="TA">Tristan de Cunha  +290</option>
								<option value="216" data-country="TN">Tunisia 🇹🇳 +216</option>
								<option value="90" data-country="TR">Turkey 🇹🇷 +90</option>
								<option value="993" data-country="TM">Turkmenistan 🇹🇲 +993</option>
								<option value="1649" data-country="TC">Turks And Caicos Islands 🇹🇨 +1 649</option>
								<option value="688" data-country="TV">Tuvalu 🇹🇻 +688</option>
								<option value="256" data-country="UG">Uganda 🇺🇬 +256</option>
								<option value="380" data-country="UA">Ukraine 🇺🇦 +380</option>
								<option value="1" data-country="UM">United States Minor Outlying Islands 🇺🇲 +1</option>
								<option value="598" data-country="UY">Uruguay 🇺🇾 +598</option>
								<option value="998" data-country="UZ">Uzbekistan 🇺🇿 +998</option>
								<option value="678" data-country="VU">Vanuatu 🇻🇺 +678</option>
								<option value="379" data-country="VA">Vatican City State 🇻🇦 +379</option>
								<option value="39" data-country="VA">Vatican City State 🇻🇦 +39</option>
								<option value="58" data-country="VE">Venezuela, Bolivarian Republic Of 🇻🇪 +58</option>
								<option value="84" data-country="VN">Viet Nam 🇻🇳 +84</option>
								<option value="1284" data-country="VG">Virgin Islands (British) 🇻🇬 +1 284</option>
								<option value="1340" data-country="VI">Virgin Islands (US) 🇻🇮 +1 340</option>
								<option value="681" data-country="WF">Wallis And Futuna 🇼🇫 +681</option>
								<option value="212" data-country="EH">Western Sahara 🇪🇭 +212</option>
								<option value="967" data-country="YE">Yemen 🇾🇪 +967</option>
								<option value="260" data-country="ZM">Zambia 🇿🇲 +260</option>
								<option value="263" data-country="ZW">Zimbabwe 🇿🇼 +263</option>
							</select>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0 mt-3" type="text" name="phone_number" maxlength="10" value="{{ old('phone_number') }}" autocomplete="off" />
							<input type="hidden" name="country" id="country_name" value="US">
						</div>
						<!--begin::Form group-->
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="email" name="email" value="{{ old('email') }}" autocomplete="off" />
						</div>
						<!--end::Form group-->
						<!--begin::Form group-->
						<div class="form-group">
							<div class="d-flex justify-content-between mt-n5">
								<label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
							</div>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="password" name="password" autocomplete="off" />
						</div>
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Business type</label>
							<select class="form-control form-control-solid h-auto py-7 px-5 border-0 rounded-lg font-size-h6" name="business_type" id="business_type">
								<option value="">Select business type</option>
								@foreach($businesstypes as $type)
									<option value="{{ $type->id }}">{{ $type->name }}</option>
								@endforeach
							</select>
						</div>	
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Timezone</label>
							<select name="timezone" id="timezone" class="form-control form-control-solid h-auto py-7 px-5 border-0 rounded-lg font-size-h6">
								<option value="Pacific/Pago_Pago">(GMT -11:00) Pago Pago</option>
								<option value="Pacific/Niue">(GMT -11:00) Niue</option>
								<option value="Pacific/Midway">(GMT -11:00) Midway</option>
								<option value="Pacific/Rarotonga">(GMT -10:00) Rarotonga</option>
								<option value="Pacific/Tahiti">(GMT -10:00) Tahiti</option>
								<option value="America/Adak">(GMT -10:00) Adak</option>
								<option value="Pacific/Honolulu">(GMT -10:00) Honolulu</option>
								<option value="Pacific/Marquesas">(GMT -09:30) Marquesas</option>
								<option value="Pacific/Gambier">(GMT -09:00) Gambier</option>
								<option value="America/Anchorage">(GMT -09:00) Anchorage</option>
								<option value="America/Juneau">(GMT -09:00) Juneau</option>
								<option value="America/Sitka">(GMT -09:00) Sitka</option>
								<option value="America/Metlakatla">(GMT -09:00) Metlakatla</option>
								<option value="America/Yakutat">(GMT -09:00) Yakutat</option>
								<option value="America/Nome">(GMT -09:00) Nome</option>
								<option value="America/Vancouver">(GMT -08:00) Vancouver</option>
								<option value="America/Whitehorse">(GMT -08:00) Whitehorse</option>
								<option value="America/Dawson">(GMT -08:00) Dawson</option>
								<option value="America/Tijuana">(GMT -08:00) Tijuana</option>
								<option value="Pacific/Pitcairn">(GMT -08:00) Pitcairn</option>
								<option value="America/Los_Angeles">(GMT -08:00) Los Angeles</option>
								<option value="America/Edmonton">(GMT -07:00) Edmonton</option>
								<option value="America/Cambridge_Bay">(GMT -07:00) Cambridge Bay</option>
								<option value="America/Yellowknife">(GMT -07:00) Yellowknife</option>
								<option value="America/Inuvik">(GMT -07:00) Inuvik</option>
								<option value="America/Creston">(GMT -07:00) Creston</option>
								<option value="America/Dawson_Creek">(GMT -07:00) Dawson Creek</option>
								<option value="America/Fort_Nelson">(GMT -07:00) Fort Nelson</option>
								<option value="America/Mazatlan">(GMT -07:00) Mazatlan</option>
								<option value="America/Chihuahua">(GMT -07:00) Chihuahua</option>
								<option value="America/Ojinaga">(GMT -07:00) Ojinaga</option>
								<option value="America/Hermosillo">(GMT -07:00) Hermosillo</option>
								<option value="America/Bahia_Banderas">(GMT -07:00) Bahia Banderas</option>
								<option value="America/Denver">(GMT -07:00) Denver</option>
								<option value="America/Boise">(GMT -07:00) Boise</option>
								<option value="America/Phoenix">(GMT -07:00) Phoenix</option>
								<option value="America/Belize">(GMT -06:00) Belize</option>
								<option value="America/Winnipeg">(GMT -06:00) Winnipeg</option>
								<option value="America/Rainy_River">(GMT -06:00) Rainy River</option>
								<option value="America/Resolute">(GMT -06:00) Resolute</option>
								<option value="America/Rankin_Inlet">(GMT -06:00) Rankin Inlet</option>
								<option value="America/Regina">(GMT -06:00) Regina</option>
								<option value="America/Swift_Current">(GMT -06:00) Swift Current</option>
								<option value="Pacific/Easter">(GMT -06:00) Easter</option>
								<option value="America/Costa_Rica">(GMT -06:00) Costa Rica</option>
								<option value="Pacific/Galapagos">(GMT -06:00) Galapagos</option>
								<option value="America/Guatemala">(GMT -06:00) Guatemala</option>
								<option value="America/Tegucigalpa">(GMT -06:00) Tegucigalpa</option>
								<option value="America/Mexico_City">(GMT -06:00) Mexico City</option>
								<option value="America/Merida">(GMT -06:00) Merida</option>
								<option value="America/Monterrey">(GMT -06:00) Monterrey</option>
								<option value="America/Matamoros">(GMT -06:00) Matamoros</option>
								<option value="America/Managua">(GMT -06:00) Managua</option>
								<option value="America/El_Salvador">(GMT -06:00) El Salvador</option>
								<option value="America/Chicago">(GMT -06:00) Chicago</option>
								<option value="America/Indiana/Tell_City">(GMT -06:00) Tell City, Indiana</option>
								<option value="America/Indiana/Knox">(GMT -06:00) Knox, Indiana</option>
								<option value="America/Menominee">(GMT -06:00) Menominee</option>
								<option value="America/North_Dakota/Center">(GMT -06:00) Center, North Dakota</option>
								<option value="America/North_Dakota/New_Salem">(GMT -06:00) New Salem, North Dakota</option>
								<option value="America/North_Dakota/Beulah">(GMT -06:00) Beulah, North Dakota</option>
								<option value="America/Eirunepe">(GMT -05:00) Eirunepe</option>
								<option value="America/Rio_Branco">(GMT -05:00) Rio Branco</option>
								<option value="America/Nassau">(GMT -05:00) Nassau</option>
								<option value="America/Toronto">(GMT -05:00) Toronto</option>
								<option value="America/Nipigon">(GMT -05:00) Nipigon</option>
								<option value="America/Thunder_Bay">(GMT -05:00) Thunder Bay</option>
								<option value="America/Iqaluit">(GMT -05:00) Iqaluit</option>
								<option value="America/Pangnirtung">(GMT -05:00) Pangnirtung</option>
								<option value="America/Atikokan">(GMT -05:00) Atikokan</option>
								<option value="America/Bogota">(GMT -05:00) Bogota</option>
								<option value="America/Havana">(GMT -05:00) Havana</option>
								<option value="America/Guayaquil">(GMT -05:00) Guayaquil</option>
								<option value="America/Port-au-Prince">(GMT -05:00) Port-au-Prince</option>
								<option value="America/Jamaica">(GMT -05:00) Jamaica</option>
								<option value="America/Cayman">(GMT -05:00) Cayman</option>
								<option value="America/Cancun">(GMT -05:00) Cancun</option>
								<option value="America/Panama">(GMT -05:00) Panama</option>
								<option value="America/Lima">(GMT -05:00) Lima</option>
								<option value="America/Grand_Turk">(GMT -05:00) Grand Turk</option>
								<option value="America/New_York">(GMT -05:00) New York</option>
								<option value="America/Detroit">(GMT -05:00) Detroit</option>
								<option value="America/Kentucky/Louisville">(GMT -05:00) Louisville, Kentucky</option>
								<option value="America/Kentucky/Monticello">(GMT -05:00) Monticello, Kentucky</option>
								<option value="America/Indiana/Indianapolis">(GMT -05:00) Indianapolis, Indiana</option>
								<option value="America/Indiana/Vincennes">(GMT -05:00) Vincennes, Indiana</option>
								<option value="America/Indiana/Winamac">(GMT -05:00) Winamac, Indiana</option>
								<option value="America/Indiana/Marengo">(GMT -05:00) Marengo, Indiana</option>
								<option value="America/Indiana/Petersburg">(GMT -05:00) Petersburg, Indiana</option>
								<option value="America/Indiana/Vevay">(GMT -05:00) Vevay, Indiana</option>
								<option value="America/Antigua">(GMT -04:00) Antigua</option>
								<option value="America/Anguilla">(GMT -04:00) Anguilla</option>
								<option value="America/Aruba">(GMT -04:00) Aruba</option>
								<option value="America/Barbados">(GMT -04:00) Barbados</option>
								<option value="America/St_Barthelemy">(GMT -04:00) St Barthelemy</option>
								<option value="Atlantic/Bermuda">(GMT -04:00) Bermuda</option>
								<option value="America/La_Paz">(GMT -04:00) La Paz</option>
								<option value="America/Kralendijk">(GMT -04:00) Kralendijk</option>
								<option value="America/Campo_Grande">(GMT -04:00) Campo Grande</option>
								<option value="America/Cuiaba">(GMT -04:00) Cuiaba</option>
								<option value="America/Porto_Velho">(GMT -04:00) Porto Velho</option>
								<option value="America/Boa_Vista">(GMT -04:00) Boa Vista</option>
								<option value="America/Manaus">(GMT -04:00) Manaus</option>
								<option value="America/Halifax">(GMT -04:00) Halifax</option>
								<option value="America/Glace_Bay">(GMT -04:00) Glace Bay</option>
								<option value="America/Moncton">(GMT -04:00) Moncton</option>
								<option value="America/Goose_Bay">(GMT -04:00) Goose Bay</option>
								<option value="America/Blanc-Sablon">(GMT -04:00) Blanc-Sablon</option>
								<option value="America/Santiago">(GMT -04:00) Santiago</option>
								<option value="America/Curacao">(GMT -04:00) Curacao</option>
								<option value="America/Dominica">(GMT -04:00) Dominica</option>
								<option value="America/Santo_Domingo">(GMT -04:00) Santo Domingo</option>
								<option value="America/Grenada">(GMT -04:00) Grenada</option>
								<option value="America/Thule">(GMT -04:00) Thule</option>
								<option value="America/Guadeloupe">(GMT -04:00) Guadeloupe</option>
								<option value="America/Guyana">(GMT -04:00) Guyana</option>
								<option value="America/St_Kitts">(GMT -04:00) St Kitts</option>
								<option value="America/St_Lucia">(GMT -04:00) St Lucia</option>
								<option value="America/Marigot">(GMT -04:00) Marigot</option>
								<option value="America/Martinique">(GMT -04:00) Martinique</option>
								<option value="America/Montserrat">(GMT -04:00) Montserrat</option>
								<option value="America/Puerto_Rico">(GMT -04:00) Puerto Rico</option>
								<option value="America/Asuncion">(GMT -04:00) Asuncion</option>
								<option value="America/Lower_Princes">(GMT -04:00) Lower Princes</option>
								<option value="America/Port_of_Spain">(GMT -04:00) Port of Spain</option>
								<option value="America/St_Vincent">(GMT -04:00) St Vincent</option>
								<option value="America/Caracas">(GMT -04:00) Caracas</option>
								<option value="America/Tortola">(GMT -04:00) Tortola</option>
								<option value="America/St_Thomas">(GMT -04:00) St Thomas</option>
								<option value="America/St_Johns">(GMT -03:30) St Johns</option>
								<option value="Antarctica/Palmer">(GMT -03:00) Palmer</option>
								<option value="Antarctica/Rothera">(GMT -03:00) Rothera</option>
								<option value="America/Argentina/Buenos_Aires">(GMT -03:00) Buenos Aires, Argentina</option>
								<option value="America/Argentina/Cordoba">(GMT -03:00) Cordoba, Argentina</option>
								<option value="America/Argentina/Salta">(GMT -03:00) Salta, Argentina</option>
								<option value="America/Argentina/Jujuy">(GMT -03:00) Jujuy, Argentina</option>
								<option value="America/Argentina/Tucuman">(GMT -03:00) Tucuman, Argentina</option>
								<option value="America/Argentina/Catamarca">(GMT -03:00) Catamarca, Argentina</option>
								<option value="America/Argentina/La_Rioja">(GMT -03:00) La Rioja, Argentina</option>
								<option value="America/Argentina/San_Juan">(GMT -03:00) San Juan, Argentina</option>
								<option value="America/Argentina/Mendoza">(GMT -03:00) Mendoza, Argentina</option>
								<option value="America/Argentina/San_Luis">(GMT -03:00) San Luis, Argentina</option>
								<option value="America/Argentina/Rio_Gallegos">(GMT -03:00) Rio Gallegos, Argentina</option>
								<option value="America/Argentina/Ushuaia">(GMT -03:00) Ushuaia, Argentina</option>
								<option value="America/Belem">(GMT -03:00) Belem</option>
								<option value="America/Fortaleza">(GMT -03:00) Fortaleza</option>
								<option value="America/Recife">(GMT -03:00) Recife</option>
								<option value="America/Araguaina">(GMT -03:00) Araguaina</option>
								<option value="America/Maceio">(GMT -03:00) Maceio</option>
								<option value="America/Bahia">(GMT -03:00) Bahia</option>
								<option value="America/Sao_Paulo">(GMT -03:00) Sao Paulo</option>
								<option value="America/Santarem">(GMT -03:00) Santarem</option>
								<option value="America/Punta_Arenas">(GMT -03:00) Punta Arenas</option>
								<option value="Atlantic/Stanley">(GMT -03:00) Stanley</option>
								<option value="America/Cayenne">(GMT -03:00) Cayenne</option>
								<option value="America/Godthab">(GMT -03:00) Godthab</option>
								<option value="America/Miquelon">(GMT -03:00) Miquelon</option>
								<option value="America/Paramaribo">(GMT -03:00) Paramaribo</option>
								<option value="America/Montevideo">(GMT -03:00) Montevideo</option>
								<option value="America/Noronha">(GMT -02:00) Noronha</option>
								<option value="Atlantic/South_Georgia">(GMT -02:00) South Georgia</option>
								<option value="Atlantic/Cape_Verde">(GMT -01:00) Cape Verde</option>
								<option value="America/Scoresbysund">(GMT -01:00) Scoresbysund</option>
								<option value="Atlantic/Azores">(GMT -01:00) Azores</option>
								<option value="Antarctica/Troll">(GMT +00:00) Troll</option>
								<option value="Africa/Ouagadougou">(GMT +00:00) Ouagadougou</option>
								<option value="Africa/Abidjan">(GMT +00:00) Abidjan</option>
								<option value="Africa/El_Aaiun">(GMT +00:00) El Aaiun</option>
								<option value="Atlantic/Canary">(GMT +00:00) Canary</option>
								<option value="Atlantic/Faroe">(GMT +00:00) Faroe</option>
								<option value="Europe/London">(GMT +00:00) London</option>
								<option value="Europe/Guernsey">(GMT +00:00) Guernsey</option>
								<option value="Africa/Accra">(GMT +00:00) Accra</option>
								<option value="America/Danmarkshavn">(GMT +00:00) Danmarkshavn</option>
								<option value="Africa/Banjul">(GMT +00:00) Banjul</option>
								<option value="Africa/Conakry">(GMT +00:00) Conakry</option>
								<option value="Africa/Bissau">(GMT +00:00) Bissau</option>
								<option value="Europe/Dublin">(GMT +00:00) Dublin</option>
								<option value="Europe/Isle_of_Man">(GMT +00:00) Isle of Man</option>
								<option value="Atlantic/Reykjavik">(GMT +00:00) Reykjavik</option>
								<option value="Europe/Jersey">(GMT +00:00) Jersey</option>
								<option value="Africa/Monrovia">(GMT +00:00) Monrovia</option>
								<option value="Africa/Casablanca">(GMT +00:00) Casablanca</option>
								<option value="Africa/Bamako">(GMT +00:00) Bamako</option>
								<option value="Africa/Nouakchott">(GMT +00:00) Nouakchott</option>
								<option value="Europe/Lisbon">(GMT +00:00) Lisbon</option>
								<option value="Atlantic/Madeira">(GMT +00:00) Madeira</option>
								<option value="Atlantic/St_Helena">(GMT +00:00) St Helena</option>
								<option value="Africa/Freetown">(GMT +00:00) Freetown</option>
								<option value="Africa/Dakar">(GMT +00:00) Dakar</option>
								<option value="Africa/Sao_Tome">(GMT +00:00) Sao Tome</option>
								<option value="Africa/Lome">(GMT +00:00) Lome</option>
								<option value="Europe/Andorra">(GMT +01:00) Andorra</option>
								<option value="Europe/Tirane">(GMT +01:00) Tirane</option>
								<option value="Africa/Luanda">(GMT +01:00) Luanda</option>
								<option value="Europe/Vienna">(GMT +01:00) Vienna</option>
								<option value="Europe/Sarajevo">(GMT +01:00) Sarajevo</option>
								<option value="Europe/Brussels">(GMT +01:00) Brussels</option>
								<option value="Africa/Porto-Novo">(GMT +01:00) Porto-Novo</option>
								<option value="Africa/Kinshasa">(GMT +01:00) Kinshasa</option>
								<option value="Africa/Bangui">(GMT +01:00) Bangui</option>
								<option value="Africa/Brazzaville">(GMT +01:00) Brazzaville</option>
								<option value="Europe/Zurich">(GMT +01:00) Zurich</option>
								<option value="Africa/Douala">(GMT +01:00) Douala</option>
								<option value="Europe/Prague">(GMT +01:00) Prague</option>
								<option value="Europe/Berlin">(GMT +01:00) Berlin</option>
								<option value="Europe/Busingen">(GMT +01:00) Busingen</option>
								<option value="Europe/Copenhagen">(GMT +01:00) Copenhagen</option>
								<option value="Africa/Algiers">(GMT +01:00) Algiers</option>
								<option value="Europe/Madrid">(GMT +01:00) Madrid</option>
								<option value="Africa/Ceuta">(GMT +01:00) Ceuta</option>
								<option value="Europe/Paris">(GMT +01:00) Paris</option>
								<option value="Africa/Libreville">(GMT +01:00) Libreville</option>
								<option value="Europe/Gibraltar">(GMT +01:00) Gibraltar</option>
								<option value="Africa/Malabo">(GMT +01:00) Malabo</option>
								<option value="Europe/Zagreb">(GMT +01:00) Zagreb</option>
								<option value="Europe/Budapest">(GMT +01:00) Budapest</option>
								<option value="Europe/Rome">(GMT +01:00) Rome</option>
								<option value="Europe/Vaduz">(GMT +01:00) Vaduz</option>
								<option value="Europe/Luxembourg">(GMT +01:00) Luxembourg</option>
								<option value="Europe/Monaco">(GMT +01:00) Monaco</option>
								<option value="Europe/Podgorica">(GMT +01:00) Podgorica</option>
								<option value="Europe/Skopje">(GMT +01:00) Skopje</option>
								<option value="Europe/Malta">(GMT +01:00) Malta</option>
								<option value="Africa/Niamey">(GMT +01:00) Niamey</option>
								<option value="Africa/Lagos">(GMT +01:00) Lagos</option>
								<option value="Europe/Amsterdam">(GMT +01:00) Amsterdam</option>
								<option value="Europe/Oslo">(GMT +01:00) Oslo</option>
								<option value="Europe/Warsaw">(GMT +01:00) Warsaw</option>
								<option value="Europe/Belgrade">(GMT +01:00) Belgrade</option>
								<option value="Europe/Stockholm">(GMT +01:00) Stockholm</option>
								<option value="Europe/Ljubljana">(GMT +01:00) Ljubljana</option>
								<option value="Arctic/Longyearbyen">(GMT +01:00) Longyearbyen</option>
								<option value="Europe/Bratislava">(GMT +01:00) Bratislava</option>
								<option value="Europe/San_Marino">(GMT +01:00) San Marino</option>
								<option value="Africa/Ndjamena">(GMT +01:00) Ndjamena</option>
								<option value="Africa/Tunis">(GMT +01:00) Tunis</option>
								<option value="Europe/Vatican">(GMT +01:00) Vatican</option>
								<option value="Europe/Mariehamn">(GMT +02:00) Mariehamn</option>
								<option value="Europe/Sofia">(GMT +02:00) Sofia</option>
								<option value="Africa/Bujumbura">(GMT +02:00) Bujumbura</option>
								<option value="Africa/Gaborone">(GMT +02:00) Gaborone</option>
								<option value="Africa/Lubumbashi">(GMT +02:00) Lubumbashi</option>
								<option value="Asia/Nicosia">(GMT +02:00) Nicosia</option>
								<option value="Asia/Famagusta">(GMT +02:00) Famagusta</option>
								<option value="Europe/Tallinn">(GMT +02:00) Tallinn</option>
								<option value="Africa/Cairo">(GMT +02:00) Cairo</option>
								<option value="Europe/Helsinki">(GMT +02:00) Helsinki</option>
								<option value="Europe/Athens">(GMT +02:00) Athens</option>
								<option value="Asia/Jerusalem">(GMT +02:00) Jerusalem</option>
								<option value="Asia/Amman">(GMT +02:00) Amman</option>
								<option value="Asia/Beirut">(GMT +02:00) Beirut</option>
								<option value="Africa/Maseru">(GMT +02:00) Maseru</option>
								<option value="Europe/Vilnius">(GMT +02:00) Vilnius</option>
								<option value="Europe/Riga">(GMT +02:00) Riga</option>
								<option value="Africa/Tripoli">(GMT +02:00) Tripoli</option>
								<option value="Europe/Chisinau">(GMT +02:00) Chisinau</option>
								<option value="Africa/Blantyre">(GMT +02:00) Blantyre</option>
								<option value="Africa/Maputo">(GMT +02:00) Maputo</option>
								<option value="Africa/Windhoek">(GMT +02:00) Windhoek</option>
								<option value="Asia/Gaza">(GMT +02:00) Gaza</option>
								<option value="Asia/Hebron">(GMT +02:00) Hebron</option>
								<option value="Europe/Bucharest">(GMT +02:00) Bucharest</option>
								<option value="Europe/Kaliningrad">(GMT +02:00) Kaliningrad</option>
								<option value="Africa/Kigali">(GMT +02:00) Kigali</option>
								<option value="Africa/Khartoum">(GMT +02:00) Khartoum</option>
								<option value="Asia/Damascus">(GMT +02:00) Damascus</option>
								<option value="Africa/Mbabane">(GMT +02:00) Mbabane</option>
								<option value="Europe/Kiev">(GMT +02:00) Kiev</option>
								<option value="Europe/Uzhgorod">(GMT +02:00) Uzhgorod</option>
								<option value="Europe/Zaporozhye">(GMT +02:00) Zaporozhye</option>
								<option value="Africa/Johannesburg">(GMT +02:00) Johannesburg</option>
								<option value="Africa/Lusaka">(GMT +02:00) Lusaka</option>
								<option value="Africa/Harare">(GMT +02:00) Harare</option>
								<option value="Antarctica/Syowa">(GMT +03:00) Syowa</option>
								<option value="Asia/Bahrain">(GMT +03:00) Bahrain</option>
								<option value="Europe/Minsk">(GMT +03:00) Minsk</option>
								<option value="Africa/Djibouti">(GMT +03:00) Djibouti</option>
								<option value="Africa/Asmara">(GMT +03:00) Asmara</option>
								<option value="Africa/Addis_Ababa">(GMT +03:00) Addis Ababa</option>
								<option value="Asia/Baghdad">(GMT +03:00) Baghdad</option>
								<option value="Africa/Nairobi">(GMT +03:00) Nairobi</option>
								<option value="Indian/Comoro">(GMT +03:00) Comoro</option>
								<option value="Asia/Kuwait">(GMT +03:00) Kuwait</option>
								<option value="Indian/Antananarivo">(GMT +03:00) Antananarivo</option>
								<option value="Asia/Qatar">(GMT +03:00) Qatar</option>
								<option value="Europe/Moscow">(GMT +03:00) Moscow</option>
								<option value="Europe/Simferopol">(GMT +03:00) Simferopol</option>
								<option value="Europe/Volgograd">(GMT +03:00) Volgograd</option>
								<option value="Europe/Kirov">(GMT +03:00) Kirov</option>
								<option value="Asia/Riyadh">(GMT +03:00) Riyadh</option>
								<option value="Africa/Mogadishu">(GMT +03:00) Mogadishu</option>
								<option value="Africa/Juba">(GMT +03:00) Juba</option>
								<option value="Europe/Istanbul">(GMT +03:00) Istanbul</option>
								<option value="Africa/Dar_es_Salaam">(GMT +03:00) Dar es Salaam</option>
								<option value="Africa/Kampala">(GMT +03:00) Kampala</option>
								<option value="Asia/Aden">(GMT +03:00) Aden</option>
								<option value="Indian/Mayotte">(GMT +03:00) Mayotte</option>
								<option value="Asia/Tehran">(GMT +03:30) Tehran</option>
								<option value="Asia/Dubai">(GMT +04:00) Dubai</option>
								<option value="Asia/Yerevan">(GMT +04:00) Yerevan</option>
								<option value="Asia/Baku">(GMT +04:00) Baku</option>
								<option value="Asia/Tbilisi">(GMT +04:00) Tbilisi</option>
								<option value="Indian/Mauritius">(GMT +04:00) Mauritius</option>
								<option value="Asia/Muscat">(GMT +04:00) Muscat</option>
								<option value="Indian/Reunion">(GMT +04:00) Reunion</option>
								<option value="Europe/Astrakhan">(GMT +04:00) Astrakhan</option>
								<option value="Europe/Saratov">(GMT +04:00) Saratov</option>
								<option value="Europe/Ulyanovsk">(GMT +04:00) Ulyanovsk</option>
								<option value="Europe/Samara">(GMT +04:00) Samara</option>
								<option value="Indian/Mahe">(GMT +04:00) Mahe</option>
								<option value="Asia/Kabul">(GMT +04:30) Kabul</option>
								<option value="Antarctica/Mawson">(GMT +05:00) Mawson</option>
								<option value="Asia/Aqtobe">(GMT +05:00) Aqtobe</option>
								<option value="Asia/Aqtau">(GMT +05:00) Aqtau</option>
								<option value="Asia/Atyrau">(GMT +05:00) Atyrau</option>
								<option value="Asia/Oral">(GMT +05:00) Oral</option>
								<option value="Indian/Maldives">(GMT +05:00) Maldives</option>
								<option value="Asia/Karachi">(GMT +05:00) Karachi</option>
								<option value="Asia/Yekaterinburg">(GMT +05:00) Yekaterinburg</option>
								<option value="Indian/Kerguelen">(GMT +05:00) Kerguelen</option>
								<option value="Asia/Dushanbe">(GMT +05:00) Dushanbe</option>
								<option value="Asia/Ashgabat">(GMT +05:00) Ashgabat</option>
								<option value="Asia/Samarkand">(GMT +05:00) Samarkand</option>
								<option value="Asia/Tashkent">(GMT +05:00) Tashkent</option>
								<option value="Asia/Kolkata" selected="selected">(GMT +05:30) Kolkata</option>
								<option value="Asia/Colombo">(GMT +05:30) Colombo</option>
								<option value="Asia/Kathmandu">(GMT +05:45) Kathmandu</option>
								<option value="Antarctica/Vostok">(GMT +06:00) Vostok</option>
								<option value="Asia/Dhaka">(GMT +06:00) Dhaka</option>
								<option value="Asia/Thimphu">(GMT +06:00) Thimphu</option>
								<option value="Asia/Urumqi">(GMT +06:00) Urumqi</option>
								<option value="Indian/Chagos">(GMT +06:00) Chagos</option>
								<option value="Asia/Bishkek">(GMT +06:00) Bishkek</option>
								<option value="Asia/Almaty">(GMT +06:00) Almaty</option>
								<option value="Asia/Qyzylorda">(GMT +06:00) Qyzylorda</option>
								<option value="Asia/Omsk">(GMT +06:00) Omsk</option>
								<option value="Indian/Cocos">(GMT +06:30) Cocos</option>
								<option value="Asia/Yangon">(GMT +06:30) Yangon</option>
								<option value="Antarctica/Davis">(GMT +07:00) Davis</option>
								<option value="Indian/Christmas">(GMT +07:00) Christmas</option>
								<option value="Asia/Jakarta">(GMT +07:00) Jakarta</option>
								<option value="Asia/Pontianak">(GMT +07:00) Pontianak</option>
								<option value="Asia/Phnom_Penh">(GMT +07:00) Phnom Penh</option>
								<option value="Asia/Vientiane">(GMT +07:00) Vientiane</option>
								<option value="Asia/Hovd">(GMT +07:00) Hovd</option>
								<option value="Asia/Novosibirsk">(GMT +07:00) Novosibirsk</option>
								<option value="Asia/Barnaul">(GMT +07:00) Barnaul</option>
								<option value="Asia/Tomsk">(GMT +07:00) Tomsk</option>
								<option value="Asia/Novokuznetsk">(GMT +07:00) Novokuznetsk</option>
								<option value="Asia/Krasnoyarsk">(GMT +07:00) Krasnoyarsk</option>
								<option value="Asia/Bangkok">(GMT +07:00) Bangkok</option>
								<option value="Asia/Ho_Chi_Minh">(GMT +07:00) Ho Chi Minh</option>
								<option value="Australia/Perth">(GMT +08:00) Perth</option>
								<option value="Asia/Brunei">(GMT +08:00) Brunei</option>
								<option value="Asia/Shanghai">(GMT +08:00) Shanghai</option>
								<option value="Asia/Hong_Kong">(GMT +08:00) Hong Kong</option>
								<option value="Asia/Makassar">(GMT +08:00) Makassar</option>
								<option value="Asia/Ulaanbaatar">(GMT +08:00) Ulaanbaatar</option>
								<option value="Asia/Choibalsan">(GMT +08:00) Choibalsan</option>
								<option value="Asia/Macau">(GMT +08:00) Macau</option>
								<option value="Asia/Kuala_Lumpur">(GMT +08:00) Kuala Lumpur</option>
								<option value="Asia/Kuching">(GMT +08:00) Kuching</option>
								<option value="Asia/Manila">(GMT +08:00) Manila</option>
								<option value="Asia/Irkutsk">(GMT +08:00) Irkutsk</option>
								<option value="Asia/Singapore">(GMT +08:00) Singapore</option>
								<option value="Asia/Taipei">(GMT +08:00) Taipei</option>
								<option value="Asia/Pyongyang">(GMT +08:30) Pyongyang</option>
								<option value="Australia/Eucla">(GMT +08:45) Eucla</option>
								<option value="Asia/Jayapura">(GMT +09:00) Jayapura</option>
								<option value="Asia/Tokyo">(GMT +09:00) Tokyo</option>
								<option value="Asia/Seoul">(GMT +09:00) Seoul</option>
								<option value="Pacific/Palau">(GMT +09:00) Palau</option>
								<option value="Asia/Chita">(GMT +09:00) Chita</option>
								<option value="Asia/Yakutsk">(GMT +09:00) Yakutsk</option>
								<option value="Asia/Khandyga">(GMT +09:00) Khandyga</option>
								<option value="Asia/Dili">(GMT +09:00) Dili</option>
								<option value="Australia/Broken_Hill">(GMT +09:30) Broken Hill</option>
								<option value="Australia/Adelaide">(GMT +09:30) Adelaide</option>
								<option value="Australia/Darwin">(GMT +09:30) Darwin</option>
								<option value="Antarctica/DumontDUrville">(GMT +10:00) Dumont D'Urville</option>
								<option value="Australia/Hobart">(GMT +10:00) Hobart</option>
								<option value="Australia/Currie">(GMT +10:00) Currie</option>
								<option value="Australia/Melbourne">(GMT +10:00) Melbourne</option>
								<option value="Australia/Sydney">(GMT +10:00) Sydney</option>
								<option value="Australia/Brisbane">(GMT +10:00) Brisbane</option>
								<option value="Australia/Lindeman">(GMT +10:00) Lindeman</option>
								<option value="Pacific/Chuuk">(GMT +10:00) Chuuk</option>
								<option value="Pacific/Guam">(GMT +10:00) Guam</option>
								<option value="Pacific/Saipan">(GMT +10:00) Saipan</option>
								<option value="Pacific/Port_Moresby">(GMT +10:00) Port Moresby</option>
								<option value="Asia/Vladivostok">(GMT +10:00) Vladivostok</option>
								<option value="Asia/Ust-Nera">(GMT +10:00) Ust-Nera</option>
								<option value="Australia/Lord_Howe">(GMT +10:30) Lord Howe</option>
								<option value="Antarctica/Casey">(GMT +11:00) Casey</option>
								<option value="Antarctica/Macquarie">(GMT +11:00) Macquarie</option>
								<option value="Pacific/Pohnpei">(GMT +11:00) Pohnpei</option>
								<option value="Pacific/Kosrae">(GMT +11:00) Kosrae</option>
								<option value="Pacific/Noumea">(GMT +11:00) Noumea</option>
								<option value="Pacific/Norfolk">(GMT +11:00) Norfolk</option>
								<option value="Pacific/Bougainville">(GMT +11:00) Bougainville</option>
								<option value="Asia/Magadan">(GMT +11:00) Magadan</option>
								<option value="Asia/Sakhalin">(GMT +11:00) Sakhalin</option>
								<option value="Asia/Srednekolymsk">(GMT +11:00) Srednekolymsk</option>
								<option value="Pacific/Guadalcanal">(GMT +11:00) Guadalcanal</option>
								<option value="Pacific/Efate">(GMT +11:00) Efate</option>
								<option value="Antarctica/McMurdo">(GMT +12:00) McMurdo</option>
								<option value="Pacific/Fiji">(GMT +12:00) Fiji</option>
								<option value="Pacific/Tarawa">(GMT +12:00) Tarawa</option>
								<option value="Pacific/Majuro">(GMT +12:00) Majuro</option>
								<option value="Pacific/Kwajalein">(GMT +12:00) Kwajalein</option>
								<option value="Pacific/Nauru">(GMT +12:00) Nauru</option>
								<option value="Pacific/Auckland">(GMT +12:00) Auckland</option>
								<option value="Asia/Kamchatka">(GMT +12:00) Kamchatka</option>
								<option value="Asia/Anadyr">(GMT +12:00) Anadyr</option>
								<option value="Pacific/Funafuti">(GMT +12:00) Funafuti</option>
								<option value="Pacific/Wake">(GMT +12:00) Wake</option>
								<option value="Pacific/Wallis">(GMT +12:00) Wallis</option>
								<option value="Pacific/Chatham">(GMT +12:45) Chatham</option>
								<option value="Pacific/Enderbury">(GMT +13:00) Enderbury</option>
								<option value="Pacific/Fakaofo">(GMT +13:00) Fakaofo</option>
								<option value="Pacific/Tongatapu">(GMT +13:00) Tongatapu</option>
								<option value="Pacific/Apia">(GMT +13:00) Apia</option>
								<option value="Pacific/Kiritimati">(GMT +14:00) Kiritimati</option>
							</select>
						</div>
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Currency</label>
							<select class="form-control form-control-solid h-auto py-7 px-5 border-0 rounded-lg font-size-h6" name="currency" id="currency">
								<option value="AFN">Afghan Afghani - AFN</option>
								<option value="ALL">Albanian Lek - ALL</option>
								<option value="DZD">Algerian Dinar - DZD</option>
								<option value="AOA">Angolan Kwanza - AOA</option>
								<option value="ARS">Argentine Peso - ARS</option>
								<option value="AMD">Armenian Dram - AMD</option>
								<option value="AWG">Aruban Florin - AWG</option>
								<option value="AUD">Australian Dollar - AUD</option>
								<option value="AZN">Azerbaijani Manat - AZN</option>
								<option value="BSD">Bahamian Dollar - BSD</option>
								<option value="BHD">Bahraini Dinar - BHD</option>
								<option value="BDT">Bangladeshi Taka - BDT</option>
								<option value="BBD">Barbadian Dollar - BBD</option>
								<option value="BYR">Belarusian Ruble - BYR</option>
								<option value="BZD">Belize Dollar - BZD</option>
								<option value="BMD">Bermudian Dollar - BMD</option>
								<option value="BTN">Bhutanese Ngultrum - BTN</option>
								<option value="BOB">Bolivian Boliviano - BOB</option>
								<option value="BAM">Bosnia and Herzegovina Convertible Mark - BAM</option>
								<option value="BWP">Botswana Pula - BWP</option>
								<option value="BRL">Brazilian Real - BRL</option>
								<option value="GBP">British Pound - GBP</option>
								<option value="BND">Brunei Dollar - BND</option>
								<option value="BGN">Bulgarian Lev - BGN</option>
								<option value="BIF">Burundian Franc - BIF</option>
								<option value="KHR">Cambodian Riel - KHR</option>
								<option value="CAD">Canadian Dollar - CAD</option>
								<option value="CVE">Cape Verdean Escudo - CVE</option>
								<option value="KYD">Cayman Islands Dollar - KYD</option>
								<option value="XAF">Central African Cfa Franc - XAF</option>
								<option value="XPF">Cfp Franc - XPF</option>
								<option value="CLP">Chilean Peso - CLP</option>
								<option value="CNY">Chinese Renminbi Yuan - CNY</option>
								<option value="COP">Colombian Peso - COP</option>
								<option value="KMF">Comorian Franc - KMF</option>
								<option value="CDF">Congolese Franc - CDF</option>
								<option value="CRC">Costa Rican Colón - CRC</option>
								<option value="HRK">Croatian Kuna - HRK</option>
								<option value="CUC">Cuban Convertible Peso - CUC</option>
								<option value="CUP">Cuban Peso - CUP</option>
								<option value="CZK">Czech Koruna - CZK</option>
								<option value="DKK">Danish Krone - DKK</option>
								<option value="DJF">Djiboutian Franc - DJF</option>
								<option value="DOP">Dominican Peso - DOP</option>
								<option value="XCD">East Caribbean Dollar - XCD</option>
								<option value="EGP">Egyptian Pound - EGP</option>
								<option value="ERN">Eritrean Nakfa - ERN</option>
								<option value="EEK">Estonian Kroon - EEK</option>
								<option value="ETB">Ethiopian Birr - ETB</option>
								<option value="EUR">Euro - EUR</option>
								<option value="FKP">Falkland Pound - FKP</option>
								<option value="FJD">Fijian Dollar - FJD</option>
								<option value="GMD">Gambian Dalasi - GMD</option>
								<option value="GEL">Georgian Lari - GEL</option>
								<option value="GHS">Ghanaian Cedi - GHS</option>
								<option value="GIP">Gibraltar Pound - GIP</option>
								<option value="GTQ">Guatemalan Quetzal - GTQ</option>
								<option value="GNF">Guinean Franc - GNF</option>
								<option value="GYD">Guyanese Dollar - GYD</option>
								<option value="HTG">Haitian Gourde - HTG</option>
								<option value="HNL">Honduran Lempira - HNL</option>
								<option value="HKD">Hong Kong Dollar - HKD</option>
								<option value="HUF">Hungarian Forint - HUF</option>
								<option value="ISK">Icelandic Króna - ISK</option>
								<option value="INR" selected >Indian Rupee - INR</option>
								<option value="IDR">Indonesian Rupiah - IDR</option>
								<option value="IRR">Iranian Rial - IRR</option>
								<option value="IQD">Iraqi Dinar - IQD</option>
								<option value="ILS">Israeli New Sheqel - ILS</option>
								<option value="JMD">Jamaican Dollar - JMD</option>
								<option value="JPY">Japanese Yen - JPY</option>
								<option value="JOD">Jordanian Dinar - JOD</option>
								<option value="KZT">Kazakhstani Tenge - KZT</option>
								<option value="KES">Kenyan Shilling - KES</option>
								<option value="KWD">Kuwaiti Dinar - KWD</option>
								<option value="KGS">Kyrgyzstani Som - KGS</option>
								<option value="LAK">Lao Kip - LAK</option>
								<option value="LVL">Latvian Lats - LVL</option>
								<option value="LBP">Lebanese Pound - LBP</option>
								<option value="LSL">Lesotho Loti - LSL</option>
								<option value="LRD">Liberian Dollar - LRD</option>
								<option value="LYD">Libyan Dinar - LYD</option>
								<option value="MOP">Macanese Pataca - MOP</option>
								<option value="MKD">Macedonian Denar - MKD</option>
								<option value="MGA">Malagasy Ariary - MGA</option>
								<option value="MWK">Malawian Kwacha - MWK</option>
								<option value="MYR">Malaysian Ringgit - MYR</option>
								<option value="MVR">Maldivian Rufiyaa - MVR</option>
								<option value="MRO">Mauritanian Ouguiya - MRO</option>
								<option value="MUR">Mauritian Rupee - MUR</option>
								<option value="MXN">Mexican Peso - MXN</option>
								<option value="MDL">Moldovan Leu - MDL</option>
								<option value="MNT">Mongolian Tögrög - MNT</option>
								<option value="MAD">Moroccan Dirham - MAD</option>
								<option value="MZN">Mozambican Metical - MZN</option>
								<option value="MMK">Myanmar Kyat - MMK</option>
								<option value="NAD">Namibian Dollar - NAD</option>
								<option value="NPR">Nepalese Rupee - NPR</option>
								<option value="ANG">Netherlands Antillean Gulden - ANG</option>
								<option value="TWD">New Taiwan Dollar - TWD</option>
								<option value="NZD">New Zealand Dollar - NZD</option>
								<option value="NIO">Nicaraguan Córdoba - NIO</option>
								<option value="NGN">Nigerian Naira - NGN</option>
								<option value="KPW">North Korean Won - KPW</option>
								<option value="NOK">Norwegian Krone - NOK</option>
								<option value="OMR">Omani Rial - OMR</option>
								<option value="PKR">Pakistani Rupee - PKR</option>
								<option value="PAB">Panamanian Balboa - PAB</option>
								<option value="PGK">Papua New Guinean Kina - PGK</option>
								<option value="PYG">Paraguayan Guaraní - PYG</option>
								<option value="PEN">Peruvian Nuevo Sol - PEN</option>
								<option value="PHP">Philippine Peso - PHP</option>
								<option value="PLN">Polish Złoty - PLN</option>
								<option value="QAR">Qatari Riyal - QAR</option>
								<option value="RON">Romanian Leu - RON</option>
								<option value="RUB">Russian Ruble - RUB</option>
								<option value="RWF">Rwandan Franc - RWF</option>
								<option value="SHP">Saint Helenian Pound - SHP</option>
								<option value="SVC">Salvadoran Colón - SVC</option>
								<option value="WST">Samoan Tala - WST</option>
								<option value="STD">São Tomé and Príncipe Dobra - STD</option>
								<option value="SAR">Saudi Riyal - SAR</option>
								<option value="RSD">Serbian Dinar - RSD</option>
								<option value="SCR">Seychellois Rupee - SCR</option>
								<option value="SLL">Sierra Leonean Leone - SLL</option>
								<option value="SGD">Singapore Dollar - SGD</option>
								<option value="SKK">Slovak Koruna - SKK</option>
								<option value="SBD">Solomon Islands Dollar - SBD</option>
								<option value="SOS">Somali Shilling - SOS</option>
								<option value="ZAR">South African Rand - ZAR</option>
								<option value="KRW">South Korean Won - KRW</option>
								<option value="LKR">Sri Lankan Rupee - LKR</option>
								<option value="SDG">Sudanese Pound - SDG</option>
								<option value="SRD">Surinamese Dollar - SRD</option>
								<option value="SZL">Swazi Lilangeni - SZL</option>
								<option value="SEK">Swedish Krona - SEK</option>
								<option value="CHF">Swiss Franc - CHF</option>
								<option value="SYP">Syrian Pound - SYP</option>
								<option value="TJS">Tajikistani Somoni - TJS</option>
								<option value="TZS">Tanzanian Shilling - TZS</option>
								<option value="THB">Thai Baht - THB</option>
								<option value="TOP">Tongan Paʻanga - TOP</option>
								<option value="TTD">Trinidad and Tobago Dollar - TTD</option>
								<option value="TND">Tunisian Dinar - TND</option>
								<option value="TRY">Turkish Lira - TRY</option>
								<option value="TMM">Turkmenistani Manat - TMM</option>
								<option value="UGX">Ugandan Shilling - UGX</option>
								<option value="UAH">Ukrainian Hryvnia - UAH</option>
								<option value="AED">United Arab Emirates Dirham - AED</option>
								<option value="USD">United States Dollar - USD</option>
								<option value="UYU">Uruguayan Peso - UYU</option>
								<option value="UZS">Uzbekistani Som - UZS</option>
								<option value="VUV">Vanuatu Vatu - VUV</option>
								<option value="VEF">Venezuelan Bolívar - VEF</option>
								<option value="VND">Vietnamese Đồng - VND</option>
								<option value="XOF">West African Cfa Franc - XOF</option>
								<option value="YER">Yemeni Rial - YER</option>
								<option value="ZMK">Zambian Kwacha - ZMK</option>
								<option value="ZWD">Zimbabwean Dollar - ZWD</option>
							</select>
						</div>	
						<div class="form-group">
							<label class="checkbox mb-0">
								<input type="checkbox" name="agree">
								<span></span>
								<div class="ml-2">I Agree the <a href="{{ route('website-terms') }}" target="_blank">terms and conditions</a>.</div>
							</label>
							<div class="fv-plugins-message-container"></div>
						</div>
						<!--end::Form group-->
						<!--begin::Action-->
						<div class="pb-lg-0 pb-5">
							<button type="submit" id="kt_login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign Up</button>
						</div>
						<!--end::Action-->
					</form>
					<!--end::Form-->
				</div>
				<!--end::Signin-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--begin::Content-->
		<!--begin::Aside-->
		<div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
			<div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url({{ asset('media/svg/illustrations/login-visual-4.svg') }}">
				<!--begin::Aside title-->
				<h3 class="pt-lg-40 pl-lg-20 pb-lg-0 pl-10 py-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">We Got
				<br />A Surprise
				<br />For You</h3>
				<!--end::Aside title-->
			</div>
		</div>
		<!--end::Aside-->
	</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
	<script src="{{ asset('js/login.js') }}"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVdjXD_FnLKsQTNU8ki6Np_YlfwU0Oyec&v=3.exp&libraries=places"></script>
	<script>
	$(document).ready( function() {	
		$("#country_code").change( function() {
			var country = $(this).find(':selected').attr('data-country');
			$("#country_name").val(country);
		});
		
		$("#country_code, #business_type, #timezone, #currency").select2({placeholder:"Select an option"});
	});	
	
	function initialize() 
	{
		var latlng = new google.maps.LatLng(28.5355161,77.39102649999995);
		var map = new google.maps.Map(document.getElementById('map'), {
			center: latlng,
			zoom: 13
		});
		
		var marker = new google.maps.Marker({
			map: map,
			position: latlng,
			draggable: true,
			anchorPoint: new google.maps.Point(0, -29)
		});
		
		var input = document.getElementById('address');
		//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		var geocoder = new google.maps.Geocoder();
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.bindTo('bounds', map);
		//var infowindow = new google.maps.InfoWindow();   
		autocomplete.addListener('place_changed', function() {
			//infowindow.close();
			marker.setVisible(false);
			var place = autocomplete.getPlace();
			if (!place.geometry) {
				window.alert("Autocomplete's returned place contains no geometry");
				return;
			}
	  
			// If the place has a geometry, then present it on a map.
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);
			}
		   
			marker.setPosition(place.geometry.location);
			marker.setVisible(true);          
			$(".map_section").show();
		
			var premise = addres = district = postal_code = city = region = county = postal_code = country = "";
			var place = autocomplete.getPlace();
			
			for (var i = 0; i < place.address_components.length; i++) 
			{
				for (var j = 0; j < place.address_components[i].types.length; j++) 
				{
					if (place.address_components[i].types[j] == "premise") 
					{
						premise = place.address_components[i].long_name+", ";
					}
					if (place.address_components[i].types[j] == "street_number") 
					{
						addres += place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "route") 
					{
						addres += " "+place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "neighborhood") 
					{
						district = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "sublocality_level_1") 
					{
						district = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "locality") 
					{
						city = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "administrative_area_level_1") 
					{
						region = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "administrative_area_level_2") 
					{
						county = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "postal_code") 
					{
						postal_code = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "country") 
					{
						country = place.address_components[i].long_name;
					}
				}
			}
			
			$('.loc_address').val(premise+""+addres);
			$('.loc_district').val(district);
			$('.loc_city').val(city);
			$('.loc_region').val(region);
			$('.loc_county').val(county);
			$('.loc_postcode').val(postal_code);
			$('.loc_country').val(country);
			
			var lat = place.geometry.location.lat();
			var lng = place.geometry.location.lng();
						
			document.getElementById('lat').value = lat;
			document.getElementById('lng').value = lng;
		});
		
		// this function will work on marker move event into map 
		google.maps.event.addListener(marker, 'dragend', function() {
			geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {        
						bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
					}
				}
			});
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	</script>
@endsection