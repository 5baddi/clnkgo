@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<div class="d-flex justify-content-center align-items-center">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form id="card-payment" action="#">
                @csrf
                <input type="hidden" name="amount" value="{{ $pack->price }}"/>
                <div class="mb-3">
                    <div class="form-label required">{{ __('dashboard.inputs.card_number') }}</div>
                    <input type="text" id="card-number" name="card-number" class="form-control card @if($errors->has('card-number')) is-invalid @endif" data-mask="0000 0000 0000 0000" data-mask-visible="true" autocomplete="off" required/>
                    @if($errors->has('card-number'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('card-number') }}
                    </div>
                    @endif
                </div>
                <div class="mb-3">
                    <div class="form-label required">{{ __('dashboard.inputs.card_name') }}</div>
                    <input type="text" class="form-control card @if($errors->has('card-name')) is-invalid @endif" id="card-name" name="card-name" required>
                    @if($errors->has('card-name'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('card-name') }}
                    </div>
                    @endif
                </div>
                <div class="row">
                <div class="col-8">
                    <div class="mb-3">
                    <label class="form-label required">{{ __('dashboard.inputs.expir_date') }}</label>
                    <div class="row g-2">
                        <div class="col">
                        <select class="form-select card @if($errors->has('card-month')) is-invalid @endif" name="card-month" id="card-expiry-month" required>
                            <option value="1">{{ __('dashboard.months.january') }}</option>
                            <option value="2">{{ __('dashboard.months.february') }}</option>
                            <option value="3">{{ __('dashboard.months.march') }}</option>
                            <option value="4">{{ __('dashboard.months.april') }}</option>
                            <option value="5">{{ __('dashboard.months.may') }}</option>
                            <option value="6">{{ __('dashboard.months.june') }}</option>
                            <option value="7">{{ __('dashboard.months.july') }}</option>
                            <option value="8">{{ __('dashboard.months.august') }}</option>
                            <option value="9">{{ __('dashboard.months.september') }}</option>
                            <option value="10">{{ __('dashboard.months.october') }}</option>
                            <option value="11">{{ __('dashboard.months.november') }}</option>
                            <option value="12">{{ __('dashboard.months.december') }}</option>
                        </select>
                        @if($errors->has('card-month'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('card-month') }}
                        </div>
                        @endif
                        </div>
                        <div class="col">
                        <select class="form-select card @if($errors->has('card-year')) is-invalid @endif" name="card-year" id="card-expiry-year" required>
                            @for ($i = 0; $i < 10; $i++)
                            <option value="{{ date('Y') + $i }}">{{ date('Y') + $i }}</option>
                            @endfor
                        </select>
                        @if($errors->has('card-year'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('card-year') }}
                        </div>
                        @endif
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                    <div class="form-label required">{{ __('dashboard.inputs.cvv') }}</div>
                    <input type="number" name="card-cvv" id="card-cvv" class="form-control card @if($errors->has('card-cvv')) is-invalid @endif" size="3" aria-placeholder="CVV" placeholder="CVV" required/>
                    @if($errors->has('card-cvv'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('card-cvv') }}
                    </div>
                    @endif
                    </div>
                </div>
                </div>
                <div class="row mb-3 text-center">
                <div class="invalid-feedback d-block d-none"></div>
                @if($errors->has('amount'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('amount') }}
                </div>
                @endif
                </div>
                <div class="mt-2">
                <button id="pay-with-card-btn" type="submit" class="btn btn-twitter w-100" disabled>
                    {{ __('dashboard.buttons.pay', ['amount' => $pack->price, 'symbol' => $pack->symbol ?? '$']) }}
                </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
@endsection

@section('script')
$(function () {
    var $form = $("#card-payment");
    
    var tokenRequest = function() {
        var args = {
            sellerId: "{{ config('2checkout.seller_id') }}",
            publishableKey: "{{ config('2checkout.publishable_key') }}",
            ccNo: $('#card-number').val(),
            cvv: $("#card-cvv").val(),
            expMonth: $("#card-expiry-month").val(),
            expYear: $("#card-expiry-year").val()
        };

        TCO.requestToken(TwoCheckoutResponse, TwoCheckoutResponse, args);
    };

    $('form#card-payment').bind('submit', function (e) {
      $('#pay-with-card-btn').attr('disabled', true);
  
      var $form = $("#card-payment"),
          inputVal = ['input[type=email]', 'input[type=password]',
              'input[type=text]', 'input[type=file]',
              'textarea'
          ].join(', '),
          $inputs = $form.find('.card').find(inputVal),
          $errorStatus = $form.find('div.invalid-feedback'),
          valid = true;
      $errorStatus.addClass('d-none');
  
      $('.is-invalid').removeClass('is-invalid');
      $inputs.each(function (i, el) {
          var $input = $(el);
          if ($input.val() === '') {
              $input.parent().addClass('is-invalid');
              $errorStatus.removeClass('d-none');
              e.preventDefault();
          }
      });
  
        e.preventDefault();

        TCO.loadPubKey('{{ (app()->environment() === 'local') ? 'sandbox' : 'production' }}', function() {
            tokenRequest();
        });
    });
  
    $('.saved-card').on('click', function() {
      $('#card-number').val($(this).data('number'));
      $('#card-name').val($(this).data('name'));
      $('#card-expiry-month').val($(this).data('month'));
      $('#card-expiry-year').val($(this).data('year'));
      $('#card-cvv').val('');
    });
  
    $('input[id^=card]').on('change', function() {
      if ($(this).val() == '') {
        $('#pay-with-card-btn').attr('disabled', true);
        return;
      }
  
      $('input[id^=card]').each(function() {
        if ($(this).val() != '') {
          $('#pay-with-card-btn').attr('disabled', false);
        } else {
          $('#pay-with-card-btn').attr('disabled', true);
          return;
        }
      });
    });
  
    function TwoCheckoutResponse(data) {
        if (data.errorCode !== 200) {
            $form.find('div.invalid-feedback')
              .removeClass('d-none')
              .html(data.errorMsg);

            $('#pay-with-card-btn').attr('disabled', false);
        } else {
            var token = data.response.token.token;
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='response_token' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
  });
@endsection