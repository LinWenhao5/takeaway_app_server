<div class="bg-light p-2 rounded-3 mb-3 border border-subtle">
    <div class="d-flex justify-content-between align-items-center bg-dark text-white p-2 rounded mb-2">
        <span class="small opacity-75 text-uppercase fw-bold">@lang('pos.change')</span>
        <span class="fs-4 font-monospace fw-bold">€{{ $this->change }}</span>
    </div>
    
    <div class="input-group input-group-sm mb-2">
        <input type="text" readonly wire:model="cashReceived" 
               class="form-control text-end fw-bold fs-5" placeholder="0.00">
        <button wire:click="backspace" class="btn btn-outline-danger px-3">⌫</button>
    </div>

    <div class="row g-1">
        @foreach([1,2,3,4,5,6,7,8,9] as $num)
            <div class="col-4">
                <button wire:click="appendNumber('{{$num}}')" 
                        class="btn btn-light btn-sm w-100 border shadow-sm py-2">{{$num}}</button>
            </div>
        @endforeach
        <div class="col-4">
            <button wire:click="appendNumber('.')" class="btn btn-light btn-sm w-100 border shadow-sm py-2">.</button>
        </div>
        <div class="col-4">
            <button wire:click="appendNumber('0')" class="btn btn-light btn-sm w-100 border shadow-sm py-2">0</button>
        </div>
        <div class="col-4">
            <button wire:click="$set('cashReceived', '0')" class="btn btn-warning btn-sm w-100 border shadow-sm py-2">C</button>
        </div>
    </div>
</div>