@extends('app')
@section('body')
    @include('navbar-home')
    <section class="container mx-auto my-28">
        <div class="mx-10">
            <div id="list-product" class="grid grid-cols-4 gap-5">
                @foreach ($products as $index => $item)
                    <div
                        class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="rounded-t-lg" src="https://flowbite.com/docs/images/blog/image-1.jpg" alt="" />
                        </a>
                        <div class="p-5">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ $item->nama }}</h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Kategori : {{ $item->kategori }}
                            </p>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Warna : {{ $item->warna }}</p>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Harga : {{ $item->harga }}</p>
                            <form>
                                <input type="hidden" id="product_id_{{ $index }}" name="product_id" value="{{ $item->id }}">
                                <input type="hidden" id="harga_{{ $index }}" name="harga" value="{{ $item->harga }}">
                                <button id="pay-button_{{ $index }}"
                                    class="pay-button inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Beli Langsung
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @include('chatting')
    <div class="text-center fixed bottom-[20px] right-[20px]">
        <button
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            type="button" data-drawer-target="drawer-form" data-drawer-show="drawer-form" aria-controls="drawer-form">
            Chat
        </button>
    </div>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
      var socket = io('http://localhost:3000');

      $('#kirim').click(function() {
        let pesan = $('#description').val();
        socket.emit('chat', pesan);
      })

    var messages = document.getElementById('message');
    
    socket.on('chat', function(msg) {
    console.log(msg);
    var item = document.createElement('li');
    item.textContent = msg;
    messages.appendChild(item);
    window.scrollTo(0, document.body.scrollHeight);
  });
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.pay-button').click(function(event) {
            event.preventDefault();
            var index = $(this).attr('id').split('_')[1];

            $.post("{{ route('bayar') }}", {
                    _token: '{{ csrf_token() }}',
                    product_id: $('#product_id_' + index).val(),
                    harga: $('#harga_' + index).val(),
                },
                function(data, status) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location =
                            `/bayar/sukses/${data.snap_token}`;
                        },

                        onPending: function(result) {
                            location.reload();
                        },

                        onError: function(result) {
                            location.reload();
                        }

                    });
                    return false;
           
                });
        });
    </script>
@endsection
