@extends("superadmin.layouts.master")
@section("body")
<!-- Begin Page Content -->
<div class="container-fluid" id="bot-template-edit-app">

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5 mb-4">
            <div class="card shadow h-100 py-4 telegram-sim-card">
                <div class="card-body telegram-sim">
                    <div class="noti-bar">
                        <span class="text-gray-200">{{ $datas['current_time'] }}</span>
                        <span class="text-gray-200"> | </span>
                        <span class="text-gray-200">Bot Template</span>
                        <i class="text-gray-200 fas fa-signal float-right mx-1"></i>
                        <i class="text-gray-200 fas fa-battery-half float-right mx-1"></i>
                        <i class="text-gray-200 fas fa-wifi float-right mx-1"></i>
                    </div>
                    <div class="chat-bar">
                        <div class="chat-bar-bot-img-div">
                            <img src="{{ asset('img/'.$datas['bot_image']) }}" class="chat-bar-bot-img">
                        </div>
                        <div class="chat-bar-bot-name-div">
                            <h6 class="text-gray-200">{{ $datas['bot_name'] }}</h6>
                            <span class="text-gray-200 m-0">bot</span>
                        </div>
                        <div class="chat-bar-bot-save-div">
                            <i @click="startBtnClick()" class="fas fa-arrow-alt-circle-right text-gray-200 mr-3 saveBtn"></i>
                            <i @click="saveBtnOnClick()" class="fas fa-save text-gray-200 mr-3 saveBtn"></i>
                        </div>
                    </div>
                    <div class="message-box-div">
                        <div v-if="buttonMessage !== null" class="message-div-row button-message-row">
                            <div v-for="buttons in buttonMessage" class="row mb-1">
                                <div v-for="button in buttons" class="inline-btn-div" :class="[ buttons.length>0 ? 'col-'+(parseInt(12/buttons.length)): '' ]">
                                    <button class="btn btn-dark button-message" @click="buttonOnClick(button)">@{{ button["text"] }}</button>
                                </div>
                            </div>
                        </div>
                        <div v-if="photoMessage !== null" class="message-div-row photo-message-row">
                            <div class="message-div bot-msg-div">
                                {{-- <img class="photo-message" src="{{ asset('img/baydin1875logo.png') }}" /> --}}
                                <img class="photo-message" v-bind:src="photoMessage? photoMessage : '{{ asset('img/robot1.jpg') }}'" />
                                <p v-if="captionMessage !== null" class="message photo-caption">@{{ captionMessage }}</p>
                            </div>
                        </div>
                        <div v-if="animationMessage !== null" class="message-div-row animation-message-row">
                            <div class="message-div bot-msg-div">
                                {{-- <img class="photo-message" src="{{ asset('img/baydin1875logo.png') }}" /> --}}
                                <img class="photo-message" v-bind:src="animationMessage? animationMessage : '{{ asset('img/mikasa.gif') }}'" />
                                <p v-if="captionMessage !== null" class="message photo-caption">@{{ captionMessage }}</p>
                            </div>
                        </div>
                        <div v-if="textMessage !== null" class="message-div-row text-message-row">
                            <div class="message-div bot-msg-div">
                                <p class="message m-0">@{{ textMessage }}</p>
                            </div>
                        </div>
                        <div v-if="messageKey !== null && messageKey.substring(0, 13) != 'uuid-callback'" class="message-div-row">
                            <div class="message-div bot-user-msg-div">
                                <p class="message m-0" :class="[ messageKey == '/start'? 'start-key-word' : '' ]">@{{ messageKey }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="button-div start-btn-div">
                        <button @click="startBtnClick()" class="btn start-button"><span>START</span></button>
                    </div>
                    <div class="button-div msg-input-box">
                        <textarea name="message_input_box" id="message-input-box" cols="30" rows="10" class="form-control message-input-box" placeholder="Message"></textarea>
                        <div v-if="keyboardMessage !== null" class="keyboard-message-row">
                            <div v-for="keyboardBtns in keyboardMessage" class="row mb-1">
                                <div v-for="keyboardBtn in keyboardBtns" class="keyboard-btn-div" :class="[ keyboardBtns.length>0 ? 'col-'+(parseInt(12/keyboardBtns.length)): '' ]">
                                    <button class="btn btn-dark keyboard-button" @click="keyboardButtonOnClick(keyboardBtn['text'])">@{{ keyboardBtn["text"] }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7 col-md-7 mb-4 bot-control-column">
            <div class="card shadow bot-template-control-card mb-4">
                <div class="card-body">
                    <div class="control-group">
                        <div class="row justify-content-center">
                            <div class="col-xl-7">
                                <span>Message Types</span>
                                <div class="row mt-2">
                                    <div class="col-lg-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input cb-text" value="text" v-model="checkedRadioMessage">Text
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input cb-image" value="image" v-model="checkedRadioMessage">Image
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input cb-gif" value="gif" v-model="checkedRadioMessage">Gif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5">
                                <span>Keyboards</span>
                                <div class="row mt-2">
                                    <div class="col-lg-6">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input cb-button" value="button" v-model="checkedButton">Button
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input cb-keyboard" value="keyboard" v-model="checkedKeyboard">Keyboard
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div v-if="textMessage !== null" class="form-group message-text-form-group">
                        <label for="text-bot-message">Reply Text:</label>
                        <input type="text" class="form-control text-bot-message" placeholder="Text Message" id="text-bot-message" v-model="textMessage">
                    </div>
                    {{-- <div class="form-group message-text-form-group"> --}}
                    <div v-if="photoMessage !== null" class="form-group message-text-form-group">
                        <label for="text-bot-message">Photo:</label>
                        <input type="text" class="form-control text-bot-message" placeholder="Caption For Image" id="text-bot-message" v-model="captionMessage">
                        <div class="custom-file mt-2">
                            <input type="file" class="custom-file-input" id="photoFile" @change="photoOnChange">
                            <label class="custom-file-label" for="photoFile">Choose file</label>
                        </div>
                    </div>
                    <div v-if="animationMessage !== null" class="form-group message-text-form-group">
                        <label for="text-bot-message">Gif:</label>
                        <input type="text" class="form-control text-bot-message" placeholder="Caption For Gif" id="text-bot-message" v-model="captionMessage">
                        <div class="custom-file mt-2">
                            <input type="file" class="custom-file-input" id="animationFile" @change="animationOnChange">
                            <label class="custom-file-label" for="animationFile">Choose file</label>
                        </div>
                    </div>
                    <div v-if="keyboardMessage != null" class="form-group message-text-form-group">
                        <div class="keyboard-title-div">
                            <label>Keyboard:</label>
                            <div class="btn-group float-right">
                                <button class="btn btn-outline-primary add-new-row" @click="removeKeyboardRowBtnOnClick()">Remove Row</button>
                                <button class="btn btn-primary add-new-row" @click="addKeyboardRowBtnOnClick()">Add Row</button>
                            </div>
                        </div>
                        <div v-for="keyboardBtns in keyboardMessage" class="row mb-2" style="background: #f5f5f5; padding-top: 10px; padding-bottom: 10px; border-radius: 5px;">
                            <div v-for="keyboardBtn in keyboardBtns" class="mt-1" :class="[ keyboardBtns.length>0 ? 'col-xl-'+(parseInt(12/keyboardBtns.length)): '' ]">
                                <input type="text" class="form-control text-bot-message" placeholder="Button" id="text-bot-message" v-model="keyboardBtn['text']">
                            </div>
                            <div class="btn-group mb-2" style="margin-left: 0.75rem">
                                <button class="btn btn-outline-primary add-new-column" @click="removeKeyboardColumnBtnOnClick(keyboardBtns)">-</button>
                                <button class="btn btn-primary add-new-column" @click="addKeyboardColumnBtnOnClick(keyboardBtns)">+</button>
                            </div>
                        </div>
                    </div>
                    <div v-if="buttonMessage != null" class="form-group message-text-form-group">
                        <div class="button-title-div">
                            <label>Buttons:</label>
                            <div class="btn-group float-right">
                                <button class="btn btn-outline-primary add-new-row" @click="removeButtonRowBtnOnClick()">Remove Row</button>
                                <button class="btn btn-primary add-new-row" @click="addButtonRowBtnOnClick()">Add Row</button>
                            </div>
                        </div>
                        <div v-for="buttons in buttonMessage" class="row mb-2" style="background: #f5f5f5; padding-top: 10px; padding-bottom: 10px; border-radius: 5px;">
                            <div v-for="button in buttons" class="mt-2 mb-4" :class="[ buttons.length>0 ? 'col-xl-'+(parseInt(12/buttons.length)): '' ]">
                                <input type="text" class="form-control text-bot-message" placeholder="Button" id="text-bot-message" v-model="button['text']">
                                <select class="form-control mt-1" v-if="buttonSelected !== null" @change="btnSelectOnChange($event, buttonMessage.indexOf(buttons), buttons.indexOf(button))">
                                    <option :selected="buttonSelected[buttonMessage.indexOf(buttons)][buttons.indexOf(button)] == 'Reply'? 'selected': null">Reply</option>
                                    <option :selected="buttonSelected[buttonMessage.indexOf(buttons)][buttons.indexOf(button)] == 'URL'? 'selected': null">URL</option>
                                </select>
                                <input v-if="'url' in button" type="text" class="form-control text-bot-message mt-1" placeholder="Button" id="text-bot-message" v-model="button['url']">
                            </div>
                            <div class="btn-group mb-2" style="margin-left: 0.75rem">
                                <button class="btn btn-outline-primary add-new-column" @click="removeButtonColumnBtnOnClick(buttons)">-</button>
                                <button class="btn btn-primary add-new-column" @click="addButtonColumnBtnOnClick(buttons)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <textarea name="jsondata" id="" cols="30" rows="15" class="form-control" disabled>@{{ botFlow }}</textarea>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>

const { createApp } = Vue;
// import Swal from 'sweetalert2';

createApp({
    props: ['bot_id'],
    data() {
        return {
            messageBoxHeight: null,
            buttonDivHeight: null,
            botId: null,
            botFlow: '',
            messageKey: null,
            textMessage: null,
            captionMessage: null,
            photoMessage: null,
            animationMessage: null,
            checkedRadioMessage: null,
            buttonMessage: null,
            buttonSelected: [],
            checkedButton: null,
            keyboardMessage: null,
            checkedKeyboard: null
        }
    },
    methods: {
        getBotFlowData() {
            // get bot flow (json) to load the template
            axios
                .post('/api/get/botflow', {
                    'bot_id': this.botId
                }).then(response => {
                    this.botFlow = response.data.bot_flow;
                }).catch(error => {
                    console.log(error);
                    return 'get-bot-flow-error';
                });
        },

        setMessageBoxSize() {
            // set height for message box to be responsive

            this.buttonDivHeight = $('.msg-input-box').height();
            // 617 is the height of original message box wihtout message input or keyboard
            this.messageBoxHeight = 617 - this.buttonDivHeight;
            $('.message-box-div').height(this.messageBoxHeight);
        },

        startBtnClick() {
            $('.start-btn-div').hide();
            $('.saveBtn').show();
            $('.msg-input-box').show();
            $('.bot-control-column').show();
            this.messageKey = '/start'
        },

        saveBtnOnClick() {

            console.log(this.botFlow);

            axios
                .post('/api/save/botflow', {
                    'bot_id': this.botId,
                    'bot_flow': this.botFlow
                }, {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }).then(response => {
                    if (response.data.status > 0) {
                        Swal.fire('Saved Successfully').then(function() {
                            location.reload();
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Saving Error',
                            text: 'Bot flow wasn\'t saved successfully!'
                        })
                    }
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Saving Error',
                        text: 'Bot flow wasn\'t saved successfully!'
                    })
                });
        },

        keyboardButtonOnClick(text) {

            this.removePhotoUI();
            this.removeAnimationUI();
            this.removeKeyboardUI();
            this.removeButtonUI();
            // this.botFlow.data[this.messageKey] = {"text": "Text Message"};

            this.checkedRadioMessage = 'text';
            this.checkedButton = false;
            this.checkedKeyboard = false;

            this.messageKey = text;
            if (!(this.messageKey in this.botFlow.data)) {
                this.botFlow.data[this.messageKey] = {"text": "Text Message"};
            }
        },

        buttonOnClick(button) {

            if ('callback_data' in button) {

                this.removePhotoUI();
                this.removeAnimationUI();
                this.removeKeyboardUI();
                this.removeButtonUI();
                // this.botFlow.data[this.messageKey] = {"text": "Text Message"};

                this.checkedRadioMessage = 'text';
                this.checkedButton = false;
                this.checkedKeyboard = false;

                this.messageKey = button['callback_data'];
                if (!(this.messageKey in this.botFlow.data)) {
                    this.botFlow.data[this.messageKey] = {"text": "Text Message"};
                }
            } else if ('url' in button) {
                window.open(button['url'], '_blank');
            }
        },

        photoOnChange(event) {

            const file = event.target.files[0];
            if (file.type != 'image/jpeg' && file.type != 'image/png') {
                Swal.fire({
                    icon: 'error',
                    title: 'File Type Error',
                    text: 'File type must be only JPG or PNG!'
                });

                return;
            }

            const blob = new Blob([file], { type: file.type });
            const blobURL = URL.createObjectURL(blob);
            this.photoMessage = blobURL;
            this.botFlow.data[this.messageKey]['file_content'] = file;
        },

        animationOnChange(event) {

            const file = event.target.files[0];
            if (file.type != 'image/gif') {
                Swal.fire({
                    icon: 'error',
                    title: 'File Type Error',
                    text: 'File type must be only GIF!'
                });

                return;
            }

            const blob = new Blob([file], { type: file.type });
            const blobURL = URL.createObjectURL(blob);
            this.animationMessage = blobURL;
            this.botFlow.data[this.messageKey]['file_content'] = file;
        },

        addKeyboardRowBtnOnClick() {

            if (this.keyboardMessage.length>5) {
                return;
            }

            this.keyboardMessage.push([{"text": "Button"}]);
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        removeKeyboardRowBtnOnClick() {
            if (this.keyboardMessage.length<2) {
                return;
            }

            this.keyboardMessage.pop();
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        addKeyboardColumnBtnOnClick(keyboardBtns) {

            if (keyboardBtns.length>3) {
                return;
            }

            keyboardBtns.push({"text": "Button"});
        },

        removeKeyboardColumnBtnOnClick(keyboardBtns) {

            if (keyboardBtns.length<2) {
                return;
            }

            keyboardBtns.pop();
        },

        addButtonRowBtnOnClick() {

            if (this.buttonMessage.length>5) {
                return;
            }

            this.buttonMessage.push([{"text": "Button", "url": "www.google.com"}]);
            this.buttonSelected.push(['URL']);

            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        removeButtonRowBtnOnClick() {

            if (this.buttonMessage.length<2) {
                return;
            }

            this.buttonMessage.pop();
        },

        addButtonColumnBtnOnClick(buttons) {

            if (buttons.length>3) {
                return;
            }

            buttons.push({"text": "Button", "url": "www.google.com"});
            this.buttonSelected[this.buttonSelected.length-1][buttons.length-1] = "URL";
        },

        removeButtonColumnBtnOnClick(buttons) {

            if (buttons.length<2) {
                return;
            }

            buttons.pop();
            this.buttonSelected[this.buttonSelected.length-1].pop();
        },

        btnSelectOnChange(event, row, column) {
            const selectedValue = event.target.value;
            this.buttonSelected[row][column] = event.target.value;
            // this.buttonMessage = this.buttonSelected;
            // this.botFlow.data[this.messageKey]['reply_markup']['inline_keyboard'] = this.buttonSelected;

            if (selectedValue == 'Reply') {
                this.buttonMessage[row][column] = {
                    text: 'Button',
                    callback_data: 'uuid-callback'+uuid.v4()
                }

            } else if (selectedValue == 'URL') {
                this.buttonMessage[row][column] = {
                    text: 'Button',
                    url: 'www.google.com'
                }
            }

        },

        removeText() {
            if ('text' in this.botFlow.data[this.messageKey]) {
                delete this.botFlow.data[this.messageKey]['text'];
            }

            this.textMessage = null;
            $('.text-message-row').hide();
            // $('.message-text-form-group').hide();
        },

        removePhoto() {
            if ('photo' in this.botFlow.data[this.messageKey]) {
                delete this.botFlow.data[this.messageKey]['photo'];
            }

            if ('caption' in this.botFlow.data[this.messageKey]) {
                delete this.botFlow.data[this.messageKey]['caption'];
            }

            this.removePhotoUI();
        },

        removePhotoUI() {
            this.photoMessage = null;
            this.captionMessage = null;
            $('.photo-message-row').hide();
        },

        removeAnimation() {
            if ('animation' in this.botFlow.data[this.messageKey]) {
                delete this.botFlow.data[this.messageKey]['animation'];
            }

            if ('caption' in this.botFlow.data[this.messageKey]) {
                delete this.botFlow.data[this.messageKey]['caption'];
            }

            this.removeAnimationUI();
        },

        removeAnimationUI() {
            this.animationMessage = null;
            this.captionMessage = null;
            $('.animation-message-row').hide();
        },

        removeKeyboard() {
            delete this.botFlow.data[this.messageKey]['reply_markup'];
            this.removeKeyboardUI();
        },

        removeKeyboardUI() {
            this.keyboardMessage = null;
            $('.keyboard-message-row').hide();
            // $('.message-text-form-group').hide();

            // change message box size with the increase of keyboard size
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        removeButton() {
            delete this.botFlow.data[this.messageKey]['reply_markup'];
            this.removeButtonUI();
        },

        removeButtonUI() {
            this.buttonMessage = null;
            this.buttonSelected = [];
            $('.button-message-row').hide();
            // $('.message-text-form-group').hide();

            // change message box size with the increase of keyboard size
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        templateUIRefresh() {
            if (this.messageKey in this.botFlow.data) {
                if ('text' in this.botFlow.data[this.messageKey]) {
                    // $('.message-text-form-group').show();
                    $(".cb-text").prop("checked", true);
                    this.textMessage = this.botFlow.data[this.messageKey]['text'];
                }

                if ('photo' in this.botFlow.data[this.messageKey]) {
                    // $('.message-text-form-group').show();
                    $(".cb-image").prop("checked", true);
                    this.photoMessage = this.botFlow.data[this.messageKey]['photo'];
                }

                if ('animation' in this.botFlow.data[this.messageKey]) {
                    // $('.message-text-form-group').show();
                    $(".cb-gif").prop("checked", true);
                    this.animationMessage = this.botFlow.data[this.messageKey]['animation'];
                }

                if ('reply_markup' in this.botFlow.data[this.messageKey]) {
                    if ('keyboard' in this.botFlow.data[this.messageKey]['reply_markup']) {
                        $(".cb-keyboard").prop("checked", true);
                        this.keyboardMessage = this.botFlow.data[this.messageKey]['reply_markup']['keyboard'];
                    }

                    if ('inline_keyboard' in this.botFlow.data[this.messageKey]['reply_markup']) {
                        $(".cb-button").prop("checked", true);
                        this.buttonMessage = this.botFlow.data[this.messageKey]['reply_markup']['inline_keyboard'];

                        for(let i=0; i<this.buttonMessage.length; i++) {

                            var button = new Array();
                            for(j=0; j<this.buttonMessage[i].length; j++) {

                                if ('url' in this.buttonMessage[i][j]) {
                                    button[j] = 'URL';
                                } else if ('callback_data' in this.buttonMessage[i][j]) {
                                    button[j] = 'Reply';
                                } else {
                                    button[j] = 'No Action';
                                }
                            }
                            this.buttonSelected.push(button);
                        }
                    }
                }
            }
        }
    },
    created() {

        this.botId = '{{ $datas["bot_id"] }}';
        const app = this;

        $(document).ready(function() {

            $('.saveBtn').hide();
            $('.bot-control-column').hide();
            $('.msg-input-box').hide();

            // app.messageBoxHeight = 617;
            app.buttonDivHeight = $('.button-div').height();
            app.setMessageBoxSize();

            app.getBotFlowData();

        });
    },
    watch: {
        messageKey() {
            this.templateUIRefresh();
        },

        textMessage() {

            if (this.textMessage === null) {
                return;
            }

            this.botFlow.data[this.messageKey]['text'] = this.textMessage;
            // change message box size with the increase of keyboard size
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        photoMessage() {

            if (this.photoMessage === null) {
                return;
            }

            this.botFlow.data[this.messageKey]['photo'] = this.photoMessage;
            // change message box size with the increase of keyboard size
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        animationMessage() {

            if (this.animationMessage === null) {
                return;
            }

            this.botFlow.data[this.messageKey]['animation'] = this.animationMessage;
            // change message box size with the increase of keyboard size
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        captionMessage() {

            if (this.captionMessage === null) {
                return;
            }

            this.botFlow.data[this.messageKey]['caption'] = this.captionMessage;
            // change message box size with the increase of keyboard size
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        keyboardMessage() {

            if (this.keyboardMessage === null) {
                return;
            }

            if (!('reply_markup' in this.botFlow.data[this.messageKey])) {
                this.botFlow.data[this.messageKey]["reply_markup"] = {
                                                                        "keyboard": this.keyboardMessage,
                                                                        "resize_keyboard": true,
                                                                        "one_time_keyboard": true
                                                                    };
            } else {
                this.botFlow.data[this.messageKey]['reply_markup']['keyboard'] = this.keyboardMessage;
            }

            // change message box size with the increase of keyboard size
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        buttonMessage() {

            if (this.buttonMessage === null) {
                return;
            }

            if (!('reply_markup' in this.botFlow.data[this.messageKey])) {
                this.botFlow.data[this.messageKey]["reply_markup"] = {
                                                                        "inline_keyboard": this.buttonMessage,
                                                                        "resize_keyboard": true,
                                                                        "one_time_keyboard": true
                                                                    };
            } else {
                this.botFlow.data[this.messageKey]['reply_markup']['inline_keyboard'] = this.buttonMessage;
            }

            // change message box size with the increase of keyboard size
            setTimeout(() => {
                this.setMessageBoxSize();
            }, 100);
        },

        checkedRadioMessage() {

            if (this.checkedRadioMessage == 'text') {

                if (!this.botFlow.data[this.messageKey]) {
                    $('.text-message-row').show();
                    return;
                }

                this.removePhoto();
                this.removeAnimation();
                if (!this.textMessage) {
                    this.textMessage = "Text Message";
                }
                $('.text-message-row').show();
                // $('.message-text-form-group').show();
            } else if (this.checkedRadioMessage == 'image') {
                if ('text' in this.botFlow.data[this.messageKey]) {
                    this.removeText();
                } else if ('animation' in this.botFlow.data[this.messageKey]) {
                    this.removeAnimation();
                }
                if (!this.photoMessage) {
                    this.photoMessage = "{{ asset('img/robot1.jpg') }}";
                }
                $('.photo-message-row').show();
            } else if (this.checkedRadioMessage == 'gif') {
                if ('text' in this.botFlow.data[this.messageKey]) {
                    this.removeText();
                } else if ('photo' in this.botFlow.data[this.messageKey]) {
                    this.removePhoto();
                }
                if (!this.animationMessage) {
                    this.animationMessage = "{{ asset('img/mikasa.gif') }}";
                }
                $('.animation-message-row').show();
            }

        },

        checkedKeyboard() {

            if (!this.botFlow.data[this.messageKey]) {
                return;
            }

            if (this.checkedKeyboard == false) {

                if ('reply_markup' in this.botFlow.data[this.messageKey]) {
                    this.removeKeyboard();
                }
            } else {

                this.checkedButton = false;
                this.keyboardMessage = [
                                            [
                                                {
                                                    "text": "Button"
                                                }
                                            ]
                                        ];
                $('.keyboard-message-row').show();
                // $('.message-text-form-group').show();
            }
        },

        checkedButton() {

            if (!this.botFlow.data[this.messageKey]) {
                return;
            }

            if (this.checkedButton == false) {

                if ('reply_markup' in this.botFlow.data[this.messageKey]) {
                    this.removeButton();
                }
            } else {

                this.checkedKeyboard = false;
                this.buttonMessage = [
                                        [
                                            {
                                                "text": "Button",
                                                "url": "https://www.google.com"
                                            }
                                        ]
                                    ];
                this.buttonSelected.push(['URL']);

                $('.button-message-row').show();
            }
        }
    }
}).mount('#bot-template-edit-app');

</script>
@endsection
