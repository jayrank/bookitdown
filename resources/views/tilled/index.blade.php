<html>
<head>
  <meta charset="utf-8">
  <link rel=icon href=https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.14/svgs/solid/credit-card.svg>
  <script src="https://js.tilled.com/v1"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

<style type="text/css">
    body { margin-top:20px; }
    .inputField {
          border: 1.5px solid #DFE3EB;
          height: 40px;
          padding-left: 10px;
          font-weight: 500;
        }
    .credit-card-box .panel-title {
        display: inline;
        font-weight: bold;
    }
    .credit-card-box .form-control.error {
        border-color: red;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(255,0,0,0.6);
    }
    .credit-card-box label.error {
      font-weight: bold;
      color: red;
      padding: 2px 8px;
      margin-top: 2px;
    }
    .credit-card-box .payment-errors {
      font-weight: bold;
      color: red;
      padding: 2px 8px;
      margin-top: 12px;
    }
    .credit-card-box label {
        display: block;
    }

    .credit-card-box .display-tr {
        display: table-row;
    }
    .credit-card-box .display-td {
        display: table-cell;
        vertical-align: middle;
        width: 50%;
    }

    .credit-card-box .panel-heading img {
        min-width: 180px;
        border-style: solid;
        border-width: 3px;
        border-color: white;
        box-shadow: 3px 3px 5px #D3D3D3;
    }

    .credit-card-font-size {
      font-size: large;
    }
    .icon{
      margin-right:auto ;
    }

    #main {
      max-width: 600px;
    }
    
  </style>
</head>
<body>
  <h3 class="text-center">Tilled Payment Example</h3>

<article id="main" class="card mx-auto">
<div class="card-body p-5">

<ul class="nav bg-light nav-pills rounded nav-fill mb-3" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="pill" href="#nav-tab-card">
    <i class="fa fa-credit-card"></i> Credit Card</a></li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="pill" href="#nav-tab-ach">
    <i class="fas fa-university"></i>  ACH</a></li>
  
</ul>

<div class="tab-content">
<div class="tab-pane fade show active" id="nav-tab-card" *ngIf="selectedPaymentType === 'card'">
<form role="form" id="payment-form" method="POST" action="javascript:void(0);">
  <div class="form-group">
    <label for="card-number-element">Card number</label>
        <div class="inputField" id="card-number-element"  autocomplete="cc-number">
        </div>
        <span class="input-group-text text-muted">
            <i id="credit-card-logo"></i>
        </span>
  </div> <!-- form-group.// -->
  <div class="row">
      <div class="col-sm-6">
          <div class="form-group">
              <label ><span class="hidden-xs">Expiration</span> </label>
            <div class="inputField" id="card-expiration-element" >
              </div>
          </div>
      </div>
      <div class="col-sm-6">
          <div class="form-group">
              <label>CVV <i class="fa fa-question-circle"></i></span></label>
              <div class="inputField" id="card-cvv-element" >
              </div>
          </div> <!-- form-group.// -->
      </div>
  </div> <!-- row.// -->
  <button class="subscribe btn btn-primary btn-block" type="button" id="submit"> Pay  </button>

  </div> <!-- tab-pane.// -->
  <div class="tab-pane fade" id="nav-tab-ach" *ngIf="selectedPaymentType === 'ach_debit'">
    <div class="row">
      <div class="col-sm-6">

    <div class="form-group">
    <label >Full name</label>
    <input type="text" class="form-control" name="username" placeholder="" required="">
    </div>
    <div class="form-group">
      <label >Address</label>
      <input type="text" class="form-control" name="address" placeholder="" required="">
    </div> <!-- form-group.// -->
    <div class="form-group" >
      <label>Account Type</label>
    <select class="form-control" id="ach_account_type" name='ach_account_type'>
      <option value="checking">Checking</option>
      <option value="savings">Savings</option>
    </select>
    </div>
    <div class="form-group">
          <label>Account Number</label>
      <div class="inputField" id="bank-account-number-element" >
          </div>
        </div>
        <div class="form-group">
            <label><span class="hidden-xs">Routing Number</span></label>
        <div class="inputField" id="bank-routing-number-element" >
            </div>
        </div> <!-- form-group.// -->    
      </div>
      </div>
  <button class="subscribe btn btn-primary btn-block" type="button" id="submit2"> Pay  </button>
</div>
</form>
</div> <!-- tab-content .// -->
</div> <!-- card-body.// -->
</article> <!-- card.// -->
<br><br>
    <h4 class="text-center">Be sure to insert your secret and publishable API keys.</h4>
    <h4 class="text-center"><a href="https://api.tilled.com/docs#section/Tilled.js" target="_blank">Tilled.js docs</a>
    </h4>
 
<br><br>
  

  <!-- Included here for simplicity of example -->
  <script>
    var account_id = 'acct_Ah9tVSLzeldtw0bhsVnv0';
    var pk_PUBLISHABLE_KEY = 'pk_jRAj6gUpVrTaElVqksMBIgVfKQkNAsyQgpQ1SIJWl0ZwFADNVJt4Kb52qbWk6wXXZHFTAQCdfrvGEo0DEdqXOCnj8ZVIiXPo1076';
    document.addEventListener('DOMContentLoaded', async () => {
    const tilledAccountId = account_id
    const tilled = new Tilled(
        pk_PUBLISHABLE_KEY, 
        tilledAccountId, 
        { 
          sandbox: true,
          log_level: 0 
        })
    
      const form = await tilled.form({
        payment_method_type: 'card',
      })
    
      // Optional styling of the fields
      const fieldOptions = {
        styles: {
          base: {
            fontFamily: 'Helvetica Neue, Arial, sans-serif',
            color: '#304166',
            fontWeight: '400',
            fontSize: '16px',
          },
          invalid: {
            ':hover': {
              textDecoration: 'underline dotted red',
            },
          },
          valid: {
            color: '#00BDA5',
          },
        },
      };

      form.createField('cardNumber', fieldOptions).inject('#card-number-element')
      form.createField('cardExpiry', fieldOptions).inject('#card-expiration-element')
      form.createField('cardCvv', fieldOptions).inject('#card-cvv-element')
    
      await form.build()
    
      const submitButton = document.getElementById('submit')
    
      form.on('validation', (event) => {
        if (event.field) {
            event.field.element.classList.remove('success')
            event.field.element.classList.remove('error')
            if (event.field.valid) {
                event.field.element.classList.add('success')
            } else {
                event.field.element.classList.add('error')
            }
        }

        submitButton.disabled = form.invalid
      })
    
      form.fields.cardNumber.on('change', () => {
        const cardBrand = form.getCardBrand()
        const icon = document.getElementById('credit-card-logo')
    
        switch (cardBrand) {
          case 'American Express':
            icon.classList = 'fa fa-cc-amex'
            break
          case 'MasterCard':
            icon.classList = 'fa fa-cc-mastercard'
            break
          case 'Visa':
            icon.classList = 'fa fa-cc-visa'
            break
          case 'Diners':
            icon.classList = 'fa fa-cc-diners-club'
            break
          case 'JCB':
            icon.classList = 'fa fa-cc-jcb'
            break
          case 'Discover':
            icon.classList = 'fa fa-cc-discover'
            break
          default:
            icon.classList = 'fa fa-credit-card'
        }
      })
    
      submitButton.addEventListener('click', async () => {
        
        submitButton.disabled = true

        // Generally gone in advance...
        // Ask server to generate PaymentIntent
        // it will send back clientSecret
        let secretResponse = await fetch('/scheduledown/submitTilled/' + tilledAccountId)
        let secretData = await secretResponse.json()
        let clientSecret = secretData.client_secret
        console.log('PaymentIntent clientSecret: ' + clientSecret)
        await tilled.confirmPayment(clientSecret, {
          // you can either pass the entire form
          // or the id of the paymentMethod
          payment_method: { 
            form,
            type: 'card',
            billing_details: {
              name: 'John Smith',
              address: {
                country: "US",
                zip: "80301",
                state: "CO",
                city: "Boulder",
                street: "2905 Pearl Street"
              }
            }
          },
        }).then(
          (payment) => {
            console.log('Successful payment.')
            console.log(payment)
            window.alert('Successful payment')
            // payment is successful, payment will contain information about the transaction that was created
          },
          (error) => {
            console.log('Failed to confirm payment.')
            console.log(error)
            // show the error to the customer
            window.alert(error)
          },
        )

      })
  }) // end of DOMContentLoaded
  document.addEventListener('DOMContentLoaded', async () => {
    const tilledAccountId = account_id
      const tilled = new Tilled(
        pk_PUBLISHABLE_KEY, 
        tilledAccountId,  
        { 
          sandbox: true,
          log_level: 0 
        })
        
      const form = await tilled.form({
        payment_method_type: 'ach_debit',
      })
      
      form.createField('bankRoutingNumber').inject('#bank-routing-number-element');
      form.createField('bankAccountNumber').inject('#bank-account-number-element');
      
      await form.build()
      
      const submitButton = document.getElementById('submit2')
    
      form.on('validation', (event) => {
        if (event.field) {
          event.field.element.classList.remove('success')
          event.field.element.classList.remove('error')
          if (event.field.valid) {
            event.field.element.classList.add('success')
          } else {
            event.field.element.classList.add('error')
          }
        }

        submitButton.disabled = form.invalid
      })
    
    
      submitButton.addEventListener('click', async () => {
        
        submitButton.disabled = true

        // Generally gone in advance...
        // Ask server to generate PaymentIntent
        // it will send back clientSecret
        let secretResponse = await fetch('/scheduledown/submitTilled/' + tilledAccountId)
        let secretData = await secretResponse.json()
        let clientSecret = secretData.client_secret

        console.log('PaymentIntent clientSecret: ' + clientSecret)


        await tilled.confirmPayment(clientSecret, {
          // you can either pass the entire form
          // or the id of the paymentMethod
          payment_method: { 
            form,
            type: 'ach_debit',
            billing_details: {
              name: 'John Smith',
              address: {
                country: "US",
                zip: "80301",
                state: "CO",
                city: "Boulder",
                street: "2905 Pearl Street"
              }
            },
            ach_debit: {
            account_type: document.getElementById('ach_account_type').value
            }
          },
        }).then(
          (payment) => {
            console.log('Successful payment.')
            console.log(payment)
            window.alert('Successful payment')
            // payment is successful, payment will contain information about the transaction that was created
          },
          (error) => {
            console.log('Failed to confirm payment.')
            console.log(error)
            // show the error to the customer
            window.alert(error)
          },
        )

      })
  }) // end of DOMContentLoaded

</script>
</body>
</html>