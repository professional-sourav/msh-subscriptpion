@if ($crud->hasAccess('create'))
    <button 
    id="plan_sync_with_stripe" 
    class="btn btn-primary" 
    onclick="syncPricesWithStripe(this)" 
    data-route="{{ route("plan.sync-with-stripe") }}"
    data-button-type="sync">
        <span class="ladda-label"><i class="fa fa-plus"></i> Sync with Stripe</span>
    </button>
@endif

@push('after_scripts')
<script>
    if (typeof syncPricesWithStripe != 'function') {
      $("[data-button-type=sync]").unbind('click');

      function syncPricesWithStripe(button) {
          // ask for confirmation before deleting an item
          // e.preventDefault();
          var button = $(button);
          var route = button.attr('data-route');

          $.ajax({
              url: route,
              type: 'GET',
              success: function(result) {
                  // Show an alert with the result
                  console.log(result,route);
                  new Noty({
                      text: "Some Tx had been imported",
                      type: "success"
                  }).show();

                  // Hide the modal, if any
                  $('.modal').modal('hide');

                  crud.table.ajax.reload();
              },
              error: function(result) {
                  // Show an alert with the result
                  new Noty({
                      text: "The new entry could not be created. Please try again.",
                      type: "warning"
                  }).show();
              }
          });
      }
    }
</script>
@endpush