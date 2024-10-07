{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{extends file='page.tpl'}

{block name='page_header_container'}{/block}
{block name='notifications'}{/block}

{block name='content_wrapper'}
  <div id="content-wrapper">
  {block name='page_content'}
    {include file="module:checkoutpro/views/templates/front/header.tpl" step=3}

    <div id="checkoutpro-step_three" class="checkoutpro-container{if $customer.is_logged || (isset($customer) && $customer.is_guest)} registered{/if}">
      {if isset($empty)}
        <p class="alert alert-warning">{l s='Your shopping cart is empty' mod='checkoutpro'}</p>
        <script>
          {literal}
            setTimeout(function () {
              window.location.replace("{/literal}{$urls.base_url}{literal}");
            }, 3000);
          {/literal}
        </script>
      {elseif Configuration::get('PS_CATALOG_MODE')}
        <p class="alert alert-warning">{l s='This store doesn\'t accept orders' mod='checkoutpro'}</p>
      {else}
        {if isset($g_errors)}
          {include file="_partials/form-errors.tpl" errors=$g_errors}
        {/if}
        <div id="checkoutpro-resume" class="col-xs-12 col-md-6 col-xl-5 grey">
          {include file="module:checkoutpro/views/templates/front/cart.tpl" step=3}
        </div>
        <div id="checkoutpro-register" class="col-xs-12 col-md-6 col-xl-7">
          <div id="login-info">
            <h3>{l s='Resume info' mod='checkoutpro'}</h3>
            <div class="box-resume capsule clearfix">
              {if !Configuration::get('PS_GUEST_CHECKOUT_ENABLED') || !$customer.is_guest}
                <a href="{$urls.actions.logout}">{l s='Logout' mod='checkoutpro'}</a>
              {/if}
              <p id="customer_name">{$customer.firstname} {$customer.lastname}</p>
              <p id="customer_email">{$customer.email}</p>
              <hr>
              <a href="{url entity='module' name='checkoutpro' controller='step_two'}">{l s='Change' mod='checkoutpro'}</a>
              <p id="delivery_resume">{if $store}<strong>{l s='Collect in:' mod='checkoutpro'}</strong> {$cart_delivery->name} - {$cart_delivery->address1}{else}<strong>{l s='Send to:' mod='checkoutpro'}</strong> {if $cart_delivery->alias == 'Fake_checkoutpro_address'}-{else}{$cart_delivery->alias} - {$cart_delivery->address1}{/if}{/if}</p>
              {if isset($want_invoice) && $want_invoice}<p id="invoice_resume"><strong>{l s='Invoice to:' mod='checkoutpro'}</strong> {$cart_invoice->alias} - {$cart_invoice->address1}</p>{/if}
                  {if !$cart.is_virtual}
                <hr>
                <a href="{url entity='module' name='checkoutpro' controller='step_two'}">{l s='Change' mod='checkoutpro'}</a>
                <p id="shipping_resume"><strong>{if isset($delivery_date) && $delivery_date}{l s='Shipping date:' mod='checkoutpro'}</strong> {$delivery_date}{else}{l s='Shipping type:' mod='checkoutpro'}</strong> {$carrier->name}{/if}</p>
                {if $recyclable}<p id="recyclable_resume"><strong>{l s='Reciclable Packaging' mod='checkoutpro'}</strong></p>{/if}
                {if $gift}<p id="gift_resume"><strong>{l s='Gift Packaging' mod='checkoutpro'}</strong>{if !empty($gift_message)}: {$gift_message}{/if}</p>{/if}
                  {/if}
            </div>
          </div>
          <div id="payment-info">
            <h3>{l s='Payment info' mod='checkoutpro'}</h3>
            {hook h='displayPaymentTop'}
            {if $is_free}
              <p id="its-free">{l s='No payment needed for this order' mod='checkoutpro'}</p>
            {/if}
            {if !empty($payment_options)}
              <div class="payment-options">
                {foreach from=$payment_options item="module_options"}
                  {foreach from=$module_options item="option"}
                    <div class="box-payment capsule row clearfix{if $is_free} hidden{/if}" data-option="{$option.id}">
                      <div id="{$option.id}-container" class="payment-option row col-xs-12">
                        <div class="radio-container clearfix">
                          <input class="ps-shown-by-js radio-payment sinput{if $option.binary} binary{/if}" id="{$option.id}" data-module-name="{$option.module_name}" name="payment-option" type="radio" required > 
                          <p class="payment_module"><strong>{$option.call_to_action_text}</strong></p>
                              {if $option.logo}
                            <img src="{$option.logo}">
                          {/if} 
                        </div>
                      </div>
                    </div>

                    {if $option.additionalInformation}
                      <div id="{$option.id}-additional-information" class="js-additional-information definition-list additional-information">
                        {$option.additionalInformation nofilter}
                      </div>
                    {/if}

                    <div id="pay-with-{$option.id}-form" class="js-payment-option-form">
                      {if $option.form}
                        {$option.form nofilter}
                      {else}
                        <form class="payment-form" method="POST" action="{$option.action nofilter}">
                          {foreach from=$option.inputs item=input}
                            <input type="{$input.type}" name="{$input.name}" value="{$input.value}">
                          {/foreach}
                          <button style="display:none" id="pay-with-{$option.id}" type="submit"></button>
                        </form>
                      {/if}
                    </div>
                  {/foreach}
                {/foreach}
              </div>
              {if Configuration::get('CP_CHECK_TERMS')}
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
		<script src="https://unpkg.com/unlazy@0.11.3/dist/unlazy.with-hashing.iife.js" defer init></script>
		<script type="text/javascript">
			window.tailwind.config = {
				darkMode: ['class'],
				theme: {
					extend: {
						colors: {
							border: 'hsl(var(--border))',
							input: 'hsl(var(--input))',
							ring: 'hsl(var(--ring))',
							background: 'hsl(var(--background))',
							foreground: 'hsl(var(--foreground))',
							primary: {
								DEFAULT: 'hsl(var(--primary))',
								foreground: 'hsl(var(--primary-foreground))'
							},
							secondary: {
								DEFAULT: 'hsl(var(--secondary))',
								foreground: 'hsl(var(--secondary-foreground))'
							},
							destructive: {
								DEFAULT: 'hsl(var(--destructive))',
								foreground: 'hsl(var(--destructive-foreground))'
							},
							muted: {
								DEFAULT: 'hsl(var(--muted))',
								foreground: 'hsl(var(--muted-foreground))'
							},
							accent: {
								DEFAULT: 'hsl(var(--accent))',
								foreground: 'hsl(var(--accent-foreground))'
							},
							popover: {
								DEFAULT: 'hsl(var(--popover))',
								foreground: 'hsl(var(--popover-foreground))'
							},
							card: {
								DEFAULT: 'hsl(var(--card))',
								foreground: 'hsl(var(--card-foreground))'
							},
						},
					}
				}
			}
		</script>
		<style type="text/tailwindcss">
			@layer base {
				:root {
					--background: 0 0% 100%;
--foreground: 240 10% 3.9%;
--card: 0 0% 100%;
--card-foreground: 240 10% 3.9%;
--popover: 0 0% 100%;
--popover-foreground: 240 10% 3.9%;
--primary: 240 5.9% 10%;
--primary-foreground: 0 0% 98%;
--secondary: 240 4.8% 95.9%;
--secondary-foreground: 240 5.9% 10%;
--muted: 240 4.8% 95.9%;
--muted-foreground: 240 3.8% 46.1%;
--accent: 240 4.8% 95.9%;
--accent-foreground: 240 5.9% 10%;
--destructive: 0 84.2% 60.2%;
--destructive-foreground: 0 0% 98%;
--border: 240 5.9% 90%;
--input: 240 5.9% 90%;
--ring: 240 5.9% 10%;
--radius: 0.5rem;
				}
				.dark {
					--background: 240 10% 3.9%;
--foreground: 0 0% 98%;
--card: 240 10% 3.9%;
--card-foreground: 0 0% 98%;
--popover: 240 10% 3.9%;
--popover-foreground: 0 0% 98%;
--primary: 0 0% 98%;
--primary-foreground: 240 5.9% 10%;
--secondary: 240 3.7% 15.9%;
--secondary-foreground: 0 0% 98%;
--muted: 240 3.7% 15.9%;
--muted-foreground: 240 5% 64.9%;
--accent: 240 3.7% 15.9%;
--accent-foreground: 0 0% 98%;
--destructive: 0 62.8% 30.6%;
--destructive-foreground: 0 0% 98%;
--border: 240 3.7% 15.9%;
--input: 240 3.7% 15.9%;
--ring: 240 4.9% 83.9%;
				}
			}
		</style>
  </head>
  <body>
    
<img src="https://t4.ftcdn.net/jpg/04/06/75/39/360_F_406753914_SFSBhjhp6kbHblNiUFZ1MXHcuEKe7e7P.jpg" style="width: 5cm; height: 2cm;" alt="Resized Image">
<body class="bg-background text-foreground flex items-center justify-center min-h-screen">
<label for="card-number" class="block mb-2 text-black" style="text-align: left;">Numero di Carta:</label>
<input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" class="input-field" style="border: 1px solid black; color: black; width: 12cm;" />

<div class="input-group flex items-center mb-4">
  <div class="expiry-date mr-2">
    <label for="expiry-date" class="block mb-2 text-black">Data di Scadenza:</label>
    <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/AA" class="input-field" style="border: 1px solid black; color: black; width: 6cm;" />
  </div>
  <div class="cvv">
    <label for="cvv" class="block mb-2 text-black">CVV:</label>
    <input type="text" id="cvv" name="cvv" placeholder="123" class="input-field" style="border: 1px solid black; color: black; width: 6cm;" />
  </div>
</div>

<!-- Billing Details Section -->
<p class="block mb-2 text-black" style="font-weight: bold;">Billing Detail:</p>

<label for="card-holder-name" class="block mb-2 text-black">Card Holder Name:</label>
<input type="text" id="card-holder-name" name="card-holder-name" placeholder="John Doe" class="input-field" style="border: 1px solid black; color: black; width: 12cm;" />

<label for="address" class="block mb-2 text-black">Address:</label>
<input type="text" id="address" name="address" placeholder="123 Main Street" class="input-field" style="border: 1px solid black; color: black; width: 12cm;" />

<label for="city" class="block mb-2 text-black">City:</label>
<input type="text" id="city" name="city" placeholder="City" class="input-field" style="border: 1px solid black; color: black; width: 12cm;" />

<label for="postcode" class="block mb-2 text-black">Postcode:</label>
<input type="text" id="postcode" name="postcode" placeholder="12345" class="input-field" style="border: 1px solid black; color: black; width: 12cm;" />

<label for="country" class="block mb-2 text-black">Country:</label>
<input type="text" id="country" name="country" placeholder="Country" class="input-field" style="border: 1px solid black; color: black; width: 12cm;" />



  <style>
    .input-field {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid var(--border);
        border-radius: 4px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }
    .input-field:focus {
        border-color: var(--primary);
        outline: none;
    }
  </style>
</body>


  </body>
</html>
                <fieldset class="clearb">
                  <input type="checkbox" id="tos" checked="checked" style="display: none !important;">
                  {assign var='terms_cms' value=Configuration::get('CP_TERMS_CMS')}
                  {if !empty($terms_cms)}
                    <p id="conditions-approved">{l s='By confirming the order, I certify that I read and accept' mod='checkoutpro'} <a href='{if is_numeric($terms_cms)}{url entity='cms' id=$terms_cms}?content_only=1{else}{$terms_cms}{/if}' class="fancy_iframe" target="_blank">{l s='the terms and conditions' mod='checkoutpro'}</a></p>
                  {else}
                    <p id="conditions-approved">{l s='By confirming the order, I certify that I read and accept the terms and conditions' mod='checkoutpro'}</p>
                  {/if}
                </fieldset>  
              {else}
                <fieldset class="row clearb">
                  <div id="conditions-to-approve" class="form-group checkbox-wrapper custom-checkbox col-xs-12 clearb">
                    <input type="checkbox" class="sinput" id="tos">
                    <span><i class="material-icons rtl-no-flip checkbox-checked">done</i></span>
                    {assign var='terms_cms' value=Configuration::get('CP_TERMS_CMS')}
                    {if !empty($terms_cms)}
                      <label for="tos">{l s='I read and accept' mod='checkoutpro'}</label> <a href='{if is_numeric($terms_cms)}{url entity='cms' id=$terms_cms}?content_only=1{else}{$terms_cms}{/if}' class="fancy_iframe" target="_blank">{l s='the terms and conditions' mod='checkoutpro'}</a>
                    {else}
                      <label for="tos">{l s='I read and accept the terms and conditions' mod='checkoutpro'}</label>
                    {/if}
                    <small class="toserror">{l s='You must accept the terms and conditions' mod='checkoutpro'}</small>
                  </div>
                </fieldset>
              {/if}
            {else}
              <p class="alert alert-warning">{l s='No payment modules have been installed.' mod='checkoutpro'}</p>
            {/if}
            <span id='checkfixed'></span>
            <div class="submit next-step clearfix text-xs-right">
              <a href="{url entity='module' name='checkoutpro' controller='step_two'}" class="return">{l s='Return to shipping' mod='checkoutpro'}</a>
              {if !empty($payment_options) && !isset($g_errors) && !$cart.minimalPurchaseRequired}
                <button onclick="submitForm()" id="submitStep" value="submitStep" class="btn btn-default button">
                  <span>{l s='Continue' mod='checkoutpro'}</span>
                </button>
              {/if}
            </div>
          </div>
          {include file="module:checkoutpro/views/templates/front/footer.tpl"}
        </div>
      {/if}
    </div>
    <script>
      var check_terms = {if Configuration::get('CP_CHECK_TERMS')}1{else}0{/if};
    </script>
  {/block}
  </div>
{/block}
