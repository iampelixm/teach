function buildQuiz(quizdata, container = '#quiz_container') {

    var quiz = $(container);
    $(quiz).find('*').remove();
    var variant_template = {};
    var question_template = {};
    var contacts_template = {};
    var message_template = {};

    var btnPrev_template = `
    <button class="m-1 btn btn-info mx-auto btnprev">Назад</button>
    `;
    var btnNext_template = `
    <button class="m-1 btn btn-info mx-auto btnnext">Далее</button>
    `;
    var btnDone_template = `
    <button class="m-1 btn btn-success mx-auto btndone" onclick="quizDone('${container}');">Завершить</button>
    `;
    var screen_controls_template = `
    <div class="controls d-flex justify-content-around">
        ${btnPrev_template}
        ${btnDone_template}
        ${btnNext_template}
    </div>
    `;

    var questions_template = `
        <div class="questions window">
            <div class="screens_container questions_container">
            </div>
            <div class="controls_container">
                ${screen_controls_template}
            </div>
        </div>
    `;

    var messages_template = `
        <div class="messages window">
            <div class="screens_container messages_container">
                <div class="screen message"></div>
            </div>
            <div class="controls_container">
                <div class="message_controls text-center">
                    <button class="m-1 btn btn-success btngoback">Вернуться</button>
                    <button class="d-none m-1 btn btn-info btnendquiz">Завершить</button>
                </div>
            </div>
        </div>
    `;

    var quiz_skeleton = `
        <div class="quiz">
        ${messages_template}
        ${questions_template}
        </div>
    `;

    question_template['radio'] = function (question) {
        return `
            <div class="screen question radio" id="${question.question_name}" data-name="${question.question_name}" data-title="${question.question_title}" data-type="${question.question_type}">
                <h4 class="title text-center">${question.question_title}</h4>
                ${question.question_describe ? `<div class="describe">${question.question_describe}</div>` : ''}
            </div>
        `;
    }

    question_template['contacts_phone'] = function (question) {
        return `
            <div class="screen question contacts" id="${question.name}" data-name="${question.name}" data-title="${question.title}" data-type="${question.type}">
                <h4 class="title text-center">${question.title}</h4>
                <div class="form-group">
                    <label for="contactsName">Ваше имя:</label>
                    <input class="form-control" type="text" name="contactName" id="contactName" required>
                </div>

                <div class="form-group">
                    <label for="contactsPhone">Номет телефона:</label>
                    <input class="form-control" type="tel" name="contactsPhone" id="contactsPhone" required>
                </div>

                <div class="item radio" data-value="terms_agre">Принимаю условия обработки персональных данных</div>         
            </div>
        `;
    }

    variant_template['radio'] = function (variant) {
        return `
            <div class="item radio" data-value="${variant}">
                ${variant}
            </div>
        `;
    }

    variant_template['check'] = function (variant) {
        return `
            <div class="item check" data-value="${variant}">
                ${variant}
            </div>
        `;
    }


    $(quiz_skeleton).appendTo(quiz);
    var questions_container = $(quiz).find('.questions_container');
    //один вопрос не будет упакован в массив, а мы ожидаем массив
    //проверим и исправим это дело
    if (!quizdata[0]) quizdata = [quizdata];
    $.each(quizdata, function (qi, question) {
        if (!question.question_name) question.question_name = 'q_' + qi;
        var question_item = question_template[question.question_type](question);
        question_item = $(question_item).appendTo(questions_container);
        if (!question.answer_variant.push) question.answer_variant = [question.answer_variant];
        if (!question.answer_break_quiz.push) question.answer_break_quiz = [question.answer_break_quiz];
        if (!question.break_message.push) question.break_message = [question.break_message];

        if (question.question_only_correct_answer == 'yes') $(question_item).data('onlycorrect', true);

        $.each(question.answer_variant, function (variant_i, variant) {
            if (!variant_template[question.question_type]) return false;
            var variant_item = $(variant_template[question.question_type](variant));
            if (question.answer_break_quiz[variant_i].trim() == 'yes') {
                $(variant_item).data('break', 'yes');
                $(variant_item).data('break-message', question.break_message[variant_i]);
            }
            if (question.answer_correct[variant_i].trim() == 'yes') {
                $(variant_item).data('correct', true);
            }
            $(variant_item).appendTo(question_item);

        });
    });

    if (quizdata.message) {
        $(quiz).data('message', quizdata.message);
    }
    else {
        $(quiz).data('message', quizdata.message);
    }
    $(quiz).find('.item').on('click', function () {
        if ($(this).hasClass('radio')) {
            $(this).parent().find('.item').removeClass('selected');
            $(this).addClass('selected');
        }
        renderQuiz(container);
    });
    $(quiz).find('.btnprev').on('click', function () {
        var this_question = $(quiz).find('.screen:visible');
        var prev_question = $(this_question).prev('.screen.question');
        if ($(prev_question).is('*')) {
            if (!$(prev_question).attr('id')) $(prev_question).attr('id', unid());
            $(quiz).data('active-screen', '#' + $(prev_question).attr('id'));
        }
        renderQuiz(quiz);
    });

    $(quiz).find('.btnnext').on('click', function () {
        var this_question = $(quiz).find('.window:visible .screen:visible');
        var next_question = $(this_question).next('.screen.question');
        if ($(next_question).is('*')) {
            if (!$(next_question).attr('id')) $(next_question).attr('id', unid());
            $(quiz).data('active-screen', '#' + $(next_question).attr('id'));
        }
        renderQuiz(quiz);
    });


    $(quiz).find('.btngoback').on('click', function () {
        var prev_window = $(quiz).data('prev-window');
        var prev_screen = $(quiz).data('prev-screen');

        if (!prev_window) prev_window = '.questions';
        if (!prev_screen) prev_screen = '.question:first';

        $(quiz).data('active-window', prev_window);
        $(quiz).data('active-screen', prev_screen);
        renderQuiz(quiz);
    });


    //$(controls_template).appendTo(cont);
    renderQuiz(quiz);
}

function selectItem(quiz_container) {
    $(this).parent().find('.item').removeClass('selected');
    $(this).addClass('selected');
    renderQuiz(quiz_container);
}
function quizNext(id) {
    var quiz = $(id);

    var this_question = $(quiz).find('.window:visible .screen:visible');
    var next_question = $(this_question).next('.screen');
    if ($(next_question).is('*')) {
        if (!$(next_question).attr('id')) $(next_question).attr('id', unid());
        $(quiz).data('active-screen', '#' + $(next_question).attr('id'));
    }
    renderQuiz(id);
}

function quizPrev(id) {
    var quiz = $(id);
    var this_question = $(quiz).find('.screen:visible');
    var prev_question = $(this_question).prev('.screen');
    if ($(prev_question).is('*')) {
        if (!$(prev_question).attr('id')) $(prev_question).attr('id', unid());
        $(quiz).data('active-screen', '#' + $(prev_question).attr('id'));
    }
    renderQuiz(id);
}

function validateQuiz(id) {
    var result = true;
    var quiz = $(id);
    var break_flag = false;
    $(quiz).find('.window.questions .question').each(function (qi, screen) {
        if ($(screen).hasClass('question')) {
            var screen_id = $(screen).attr('id');
            if (!screen_id) {
                screen_id = unid();
                $(screen).attr('id', screen_id);
            }
            console.log('validating ', screen);
            if (!$(screen).find('.item.selected').is('*')) {
                $(quiz).data('active-screen', '#' + screen_id);
                $(quiz).data('active-window', '.questions');
                result = false;
                break_flag = true;
            }

            $(screen).find('input.invalid').removeClass('invalid')
            if ($(screen).find('input:required:not(:valid)').is('*')) {
                $(screen).find('input:required:not(:valid)').addClass('invalid');
                $(quiz).data('active-window', '.questions');
                $(quiz).data('active-screen', '#' + screen_id);
                //renderQuiz(id);
                result = false;
                break_flag = true;
            }

            //Проверяем прерывание
            $(screen).find('.item.selected').each(function (ei, item) {
                if ($(item).data('break')) {
                    var break_message = $(item).data('break-message');
                    if (!break_message) break_message = 'Спасибо за ваш ответ';
                    result = false;
                    break_flag = true;
                    quizMessage(id, break_message);
                    return false;
                }
            });
            if (break_flag) return false;
        }

        if ($(screen).is(':visible')) {
            console.log('last screen');
            return false;
        }
    });
    renderQuiz(id);
    return result;
}

function quizDone(id) {
    var quiz = $(id);
    var quizdata = {};
    var validated = true;

    $(quiz).find('.btndone:not(.disabled)').addClass('disabled');
    if (!validateQuiz(id)) {
        console.log('form not valid');
        return '';
    }

    quizdata.form_id = $(quiz).attr('id');
    quizdata.hash = MD5(quizdata.form_id);
    quizdata.page = location.href;
    quizdata.answers = [];

    $(quiz).find('.screen').each(function (qi, screen) {
        if ($(screen).hasClass('question')) {
            var answer = {};
            answer.question = {};
            //answer.question.name=$(screen).data('name');
            answer.question.title = $(screen).data('title');
            //answer.question.type=$(screen).data('type');            

            answer.answered = [];

            $(screen).find('.item.selected').each(function (i, selected) {
                var this_answer = {};
                this_answer.value = $(selected).html();
                answer.answered.push(this_answer);
            });

            $(screen).find('input').each(function (i, selected) {
                var this_answer = {};
                this_answer.name = $(selected).attr('name');
                this_answer.value = $(selected).val();
                answer.answered.push(this_answer);
            });

            quizdata.answers.push(answer);

            if ($(screen).is(':visible')) return false; //выходим на текущем а
        }

    });

    var done_message = $(quiz).data('message');
    var fail_message = `
    Приносим свои извинения, в ходе передачи данных произошла ошибка соединения с сервером и данные не были отправлены.
    Пожалуйста, попробуйте повторить отправку данных.
    `;

    if (!done_message) done_message = 'Спасибо за ваш ответ';


    //$(quiz).find('.messages').find('.message').html('обработка');
    quizMessage(quiz, '<h4 class="title text-center">Обработка</h4>');
    $.post(
        '/leadform/',
        quizdata,
        function (response) {
            quizMessage(quiz, done_message);
            //$(quiz).find('.window.messages').find('.message').html(done_message);
        },
        'json'
    ).
        fail(function (response) {
            quizMessage(quiz, fail_message);
        });
}

function quizMessage(id, message) {
    var quiz = $(id);
    if ($(quiz).data('active-window') == '.questions') {
        $(quiz).data('prev-window', $(quiz).data('active-window'));
        $(quiz).data('prev-screen', $(quiz).data('active-screen'));
    }
    $(quiz).data('active-window', '.messages');
    $(quiz).data('active-screen', '.message:first');
    $(quiz).find('.window.messages').find('.message:first').html(message);
    renderQuiz(id);
}

function renderQuiz(id) {
    var quiz = $(id);
    if (!$(quiz).data('init')) {
        $(quiz).data('active-window', '.questions');
        $(quiz).data('active-screen', ':first');
        //$(quiz).find('.btnnext').show();
        $(quiz).data('init', true);
    }

    $(quiz).find('.window').removeClass('active');
    $(quiz).find('.screen').removeClass('active');

    var active_window = $(quiz).find('.window' + $(quiz).data('active-window'));
    var active_screen = $(active_window).find('.screen' + $(quiz).data('active-screen'));

    $(active_window).addClass('active');
    $(active_screen).addClass('active');

    if ($(active_window).hasClass('questions')) {
        var prev_question = $(active_screen).prev('.screen.question');
        var next_question = $(active_screen).next('.screen.question');
        var controls_container = $(active_window).find('.controls_container');
        //мы не в начале квиза
        if ($(prev_question).is('*')) {
            //console.log('has prev questin');
            $(controls_container).find('.btnprev.disabled').removeClass('disabled');
        }
        else {
            $(controls_container).find('.btnprev:not(.disabled)').addClass('disabled');
        }
        //мы не в конце квиза
        if ($(next_question).is('*')) {
            //console.log('has next question');
            $(controls_container).find('.btndone:not(.disabled)').addClass('disabled');
            $(controls_container).find('.btnnext.disabled').removeClass('disabled');
        }
        //мы в конце квиза
        else {
            //console.log('has next question');
            $(controls_container).find('.btndone.disabled').removeClass('disabled');
            $(controls_container).find('.btnnext:not(.disabled)').addClass('disabled');
        }
        //не выбран ответ на текущей странице
        if (!$(active_screen).find('.item.selected').is('*')) {
            //запретить далее
            $(controls_container).find('.btnnext:not(.disabled)').addClass('disabled');
            //запретить завершение
            $(controls_container).find('.btndone:not(.disabled)').addClass('disabled');
        }

        //не выбран правильный ответ
        if ($(active_screen).data('onlycorrect')) {
            if (!$(active_screen).find('.item.selected').data('correct')) {
                //запретить далее
                $(controls_container).find('.btnnext:not(.disabled)').addClass('disabled');
                //запретить завершение
                $(controls_container).find('.btndone:not(.disabled)').addClass('disabled');
            }
        }

        //ответ завершает квиз
        if ($(active_screen).find('.item.selected').data('break')) {
            $(quiz).find('.btnnext:not(.disabled)').addClass('disabled');
            //$(quiz).find('.btnprev:not(.disabled)').addClass('disabled');
            $(quiz).find('.btndone.disabled').removeClass('disabled');
        }


    }
}