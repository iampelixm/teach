let question_defaults = {
    answer_variant: [''],
    answer_correct: ['no'],
    answer_break_quiz: ['no'],
    break_message: [''],
    question_type: 'radio',
    question_title: '',
    question_describe: '',
    question_only_correct_answer: 'no'
}

let quiz_builder_answer_variant_template = function (data) {
    var ret = '';
    if (!data.answer_variant) data.answer_variant = question_defaults.answer_variant;
    if (!data.answer_variant.push) data.answer_variant = [data.answer_variant];

    if (!data.answer_correct) data.answer_correct = question_defaults.answer_correct;
    if (!data.answer_correct.push) data.answer_correct = [data.answer_correct];

    if (!data.answer_break_quiz) data.answer_break_quiz = question_defaults.answer_break_quiz;
    if (!data.answer_break_quiz.push) data.answer_break_quiz = [data.answer_break_quiz];

    if (!data.break_message) data.break_message = question_defaults.break_message;
    if (!data.break_message.push) data.break_message = [data.break_message];

    $.each(data.answer_variant, function (vi, variant) {
        ret +=
            `
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>Вариант ответа</label>
                        <input value="${data.answer_variant[vi] ?? ''}" class="form-control" type="text" name="answer_variant" placeholder="Введите вариант ответа">
                    </div>
                </div>
                <div class="col-lg-2">
                    <label>Это правильный ответ</label>
                    <div class="form-check">
                        <input ${data.answer_correct[vi] == 'yes' ? 'checked' : ''} class="form-check-input" onchange="$(this).parent().parent().find('input[type=checkbox]').not(this).prop('checked', !$(this).prop('checked'))" type="checkbox" name="answer_correct" value="yes">
                        <label class="form-check-label">
                            Да
                        </label>            
                    </div>
                    <div class="form-check">
                        <input ${!data.answer_correct[vi] ? 'checked' : ''} ${data.answer_correct[vi] == 'no' ? 'checked' : ''} onchange="$(this).parent().parent().find('input[type=checkbox]').not(this).prop('checked', !$(this).prop('checked'))" class="form-check-input" type="checkbox" name="answer_correct" value="no">
                        <label class="form-check-label">
                            Нет
                        </label>            
                    </div>                                            
                </div>
                <div class="col-lg-2">
                    <label>Ответ прерывает квиз</label>
                    <div class="form-check">
                        <input ${data.answer_break_quiz[vi] == 'yes' ? 'checked' : ''} onchange="$(this).parent().parent().find('input[type=checkbox]').not(this).prop('checked', !$(this).prop('checked'))" class="form-check-input" type="checkbox" name="answer_break_quiz" value="yes">
                        <label class="form-check-label">
                            Да
                        </label>            
                    </div>
                    <div class="form-check">
                        <input ${!data.answer_break_quiz[vi] ? 'checked' : ''} ${data.answer_break_quiz[vi] == 'no' ? 'checked' : ''} onchange="$(this).parent().parent().find('input[type=checkbox]').not(this).prop('checked', !$(this).prop('checked'))" class="form-check-input" type="checkbox" name="answer_break_quiz" value="no">
                        <label class="form-check-label">
                            Нет
                        </label>            
                    </div>                                            
                </div>                    
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Сообщение при прерывании</label>
                        <input value="${data.break_message[vi] ?? ''}" class="form-control" type="text" name="break_message" placeholder="Введите сообщение">
                    </div>
                </div>
            </div>
        `});
    return ret;
};

let quiz_builder_question_template = function (data) {
    return `
    <form class="quiz p-2">
        <div class="quiz_question">
            <div class="form-group">
                <label>Показывать ответы</label>
                <select class="form-control" name="question_type">
                    <option value="contacts" ${data.question_type == 'contacts' ? 'selected' : ''}>Контакты</option>
                    <option value="radio" ${data.question_type == 'radio' ? 'selected' : ''}>Списком</option>
                </select>
            </div>

            <div class="form-group">
                <label>Впрос</label>
                <input class="form-control" value="${data.question_title ?? ''}" type="text" name="question_title" placeholder="Ваш вопрос">
            </div>

            <div class="form-group">
                <label>Уточнение, описание</label>
                <input class="form-control" value="${data.question_describe ?? ''}" type="text" name="question_describe" placeholder="question presc">
            </div>            
            <div class="form-group">
                <label>Только правльный ответ</label>
                <div class="form-check">
                    <input ${data.question_only_correct_answer == 'yes' ? 'checked' : ''} class="form-check-input" type="checkbox" onchange="$(this).parent().parent().find('input[type=checkbox]').not(this).prop('checked', !$(this).prop('checked'))" name="question_only_correct_answer" value="yes">
                    <label class="form-check-label">
                        Да
                    </label>            
                </div>
                <div class="form-check">
                    <input ${data.question_only_correct_answer == 'no' ? 'checked' : ''} class="form-check-input" type="checkbox" onchange="$(this).parent().parent().find('input[type=checkbox]').not(this).prop('checked', !$(this).prop('checked'))" name="question_only_correct_answer" value="no">
                    <label class="form-check-label">
                        Нет
                    </label>            
                </div>                
            </div>
            
            <h4 class="title mt-4">Варианты ответов</h4>
            <button class="addanswervariant btn btn-sm btn-info">Добавить ответ</button>
            <div class="container answer_variants">                
                ${quiz_builder_answer_variant_template(data)}
            </div>
        </div>
    </form>`
};

function quizBuilderAddQuestion(container, question_data = question_defaults) {
    var question = $(quiz_builder_question_template(question_data)).appendTo($(container));
    question.find('.addanswervariant').on('click', function (event) {
        event.preventDefault();
        $(quiz_builder_answer_variant_template(question_defaults)).appendTo($(this).next('.answer_variants'));
    });
}

function quizBuilderLoadQuiz(container, quizdata = question_defaults) {
    if (!quizdata.push) quizdata = [quizdata];
    $.each(quizdata, function (qi, question) {
        quizBuilderAddQuestion(container, question);
    });
}

function quizBuilderGetQuiz(container, link, data) {
    $.get(link, data, function (responce) {
        console.log(responce);
        if (!response) {
            quizBuilderLoadQuiz(container, question_defaults);

        }
    },
        'json').
        fail(function (r) { console.log(r) });
}

function buildQuizData(container) {
    if (!$.fn.formToJSON)
        $.fn.formToJSON = function () {
            var o = {};
            //var a = this.serializeArray();
            var all = $(this).find('select,input:checked, input[type=text], input[type=email],input[type=phone],inputtype[tel],input[type=hidden],textarea');
            $.each(all, function (ai, a) {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push($(this).prop('value') || ' ');
                } else {
                    o[this.name] = $(this).prop('value') || ' ';
                }
            });
            return o;
        };
    var quiz_data = [];
    $(container).find('form').each(function (form_i, form) {
        quiz_data.push($(form).formToJSON());
    });
    return quiz_data;
}