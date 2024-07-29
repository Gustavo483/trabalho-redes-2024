<x-app-layout>
    <div class="container__conversa">
        <section class = "conversa">
            <div class="top__bar">
                <span class=>{{$idGrupo->st_name}}</span>
            </div>
            @php
                function formatDate($isoDate) {
                    $date = new DateTime($isoDate);
                    return $date->format('d/m/Y H:i');
                }
            @endphp

            <section id="chat" class="conversa__mensagens">
                @foreach($messages as $message)
                    @php
                        $formattedDate = formatDate($message->created_at);
                    @endphp

                    <div class="{{ $user->id === $message->fk_user_send_message ? 'message--self' : 'message__other' }}">
                        <div class="flex items-center mb-2">
                            <div class="font-bold mr-2">{{ $message->userSendMessage->name }}</div>
                        </div>

                        @if($message->st_message)
                            <div>{{ $message->st_message }}</div>
                        @endif

                        @if ($message->url_file_audio)
                            @if (str_starts_with($message->st_file_type, 'audio/'))
                                <div>
                                    <audio controls>
                                        <source src="{{ $message->url_file_audio }}" type="{{ $message->st_fileType }}">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            @elseif (str_starts_with($message->st_file_type, 'image/'))
                                <div>
                                    <img src="{{ $message->url_file_audio }}" alt="{{ $message->st_name_file }}" style="width: 300px; height: 200px; border-radius: 10px">
                                </div>
                            @else
                                <div>
                                    <a href="{{ $message->url_file_audio }}" target="_blank">Abrir arquivo: "{{ $message->st_name_file }}"</a>
                                </div>
                            @endif
                            <div>
                                <a href="{{ $message->url_file_audio }}" target="_blank">Download File</a>
                            </div>
                        @endif

                        <span class="hora">{{ $formattedDate }}</span>
                    </div>
                @endforeach

            </section>

            <form class="campo__mensagem" id="messageForm" method="post" action="{{ route('sendMessege', ['group' => $idGrupo->pk_group]) }}" enctype="multipart/form-data">
                @csrf

                <input style="border: none;padding: 15px;border-radius: 8px;flex-grow: 1;background-color: #e9e9e9;outline: none; color: #191919; font-size: 1rem;" type="text" id="messege" name="st_message" placeholder="Digite uma mensagem">

                <div class="flex items-center justify-center ">
                    <label for="dropzone-file" class="">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <span class="material-symbols-outlined">attach_file</span>
                        </div>
                        <input  id="dropzone-file" type="file" class="hidden" name="file" accept="image/*,audio/*"/>
                    </label>
                </div>
                <div>

                </div>
                <button id="sendButton" type="submit" class="button__mensagem">
                    <span class="material-symbols-outlined">send</span>
                </button>
            </form>
        </section>
    </div>


    <script type="module">

        document.getElementById('messageForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const sendButton = document.getElementById('sendButton');
            sendButton.disabled = true;

            const formData = new FormData(this);

            try {
                await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData,
                });

                document.getElementById('messege').value = '';
                document.getElementById('dropzone-file').value = '';

                sendButton.disabled = false;
            } catch (error) {
                sendButton.disabled = false;
            }
        });

        const groupId = {{ $idGrupo->pk_group }};

        Echo.channel('group.' + groupId)
            .listen('.message.sent', (e) => {
                console.log(e)
                function formatDate(isoDate) {
                    const date = new Date(isoDate);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    return `${day}/${month}/${year} ${hours}:${minutes}`;
                }

                const formattedDate = formatDate(e.message.created_at);


                const user = {{$user->id}};
                var chat = document.getElementById("chat");

                let messageContent = '';

                if (e.message.st_message) {
                    messageContent += `<div>${e.message.st_message}</div>`;
                }

                if (e.message.url_file_audio) {

                    if (e.file_type.startsWith('audio/')) {
                        messageContent += `
                <div>
                    <audio controls>
                        <source src="${e.message.url_file_audio}" type="${e.file_type}">
                        Your browser does not support the audio element.
                    </audio>
                </div>`;
                    } else if (e.file_type.startsWith('image/')) {
                        messageContent += `
                <div>
                    <img src="${e.message.url_file_audio}" alt="${e.message.name_file}" style="width: 300px; height: 200px; border-radius: 10px">
                </div>`;
                    } else {
                        messageContent += `<div><a href="${e.message.url_file_audio}" target="_blank">Abrir arquivo: "${e.name_file}"</a></div>`;
                    }
                    messageContent += `<div><a href="${e.message.url_file_audio}" target="_blank">Download File</a></div>`;
                }

                if(user === e.user.id){
                    console.log('entrei aqui')
                    chat.innerHTML += `
        <div class="message--self">
            <div class="flex items-center mb-2">
                <div class="font-bold mr-2">${e.user.name}</div>
            </div>
            ${messageContent}
            <span class="hora">${formattedDate}</span>
        </div>`;
                } else {
                    chat.innerHTML += `
        <div class="message__other">
            <div class="flex items-center mb-2">
                <div class="font-bold mr-2">${e.user.name}</div>
            </div>
            ${messageContent}
            <span class="hora">${formattedDate}</span>
        </div>`;                }
            });
    </script>
</x-app-layout>
