<div class="modal fade" id="globalSearchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="background:black;margin-inline:0px; max-width:100%;">  {{-- <- centered class hata di --}}
    <div class="modal-content mt-5 border-0" style="background:black;color:#E8E8ED;    max-width: 1100px;
    margin-inline: auto;">
    

      <div class="modal-body pt-0 mt-5">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text  text-light border-0" style="background-color:black;">
              <i class="la la-search"></i>
            </span>
          </div>
          <input type="text"
                 class="form-control  text-light border-0"
                 style="background-color:black;"
                 id="globalSearchInput"
                 placeholder="{{ translate('Search by product title...') }}"
                 autocomplete="off"
                 aria-label="{{ translate('Search') }}">
          <div class="input-group-append">
            <button class="btn btn-outline-light" style="    color: #212529;
    background-color: #f8f9fa;
    border-color: #f8f9fa;" id="globalSearchClear" type="button">Clear</button>
          </div>
        </div>

        <div id="globalSearchHint" class="small opacity-60 mb-2">
          {{ translate('Type at least 2 characters') }}
        </div>

        <div id="globalSearchLoading" class="py-4 text-center d-none">
          <div class="spinner-border" role="status" aria-hidden="true"></div>
          <div class="mt-2">{{ translate('Searching...') }}</div>
        </div>

        <div id="globalSearchNoResults" class="py-4 text-center d-none">
          {{ translate('No products found') }}
        </div>

        <div id="globalSearchResults" class="list-group"></div>

        <div class="text-right mt-3">
          <a class="btn btn-sm btn-outline-light" style="    color: #212529;
    background-color: #f8f9fa;
    border-color: #f8f9fa;" id="globalSearchSeeAll" href="{{ url('/search') }}" target="_self">
            {{ translate('See all results') }}
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
