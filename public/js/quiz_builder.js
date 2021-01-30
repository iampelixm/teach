function addQuizBuilder(container) {
    let builder_template = `
    <form class="quiz p-2">
        <div class="quiz_question">
            <div class="form-group">
                <select class="form-control" name="question_type">
                    <option value="radio">Списком</option>
                </select>
            </div>

            <div class="form-group">
                <label>Впрос</label>
                <input class="form-control" type="text" name="question_title" placeholder="Ваш вопрос">
            </div>

            <div class="form-group">
                <label>Уточнение, описание</label>
                <input class="form-control" type="text" name="question_describe" placeholder="question presc">
            </div>            
            
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="question_only_correct_answer" value="yes">
                <label class="form-check-label">
                    Только правльный ответ
                </label>            
            </div>
            
            <div class="question_ansers">
                <div class="row">
                    <div class="col-lg-1">
                        <label>Добавить</label>
                        <button class="btn btn-sm btn-info" onclick="event.preventDefault(); $(this).parent().parent().clone(true).insertAfter($(this).parent().parent()).find('input').val('')">+</button>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Вариант ответа</label>
                            <input class="form-control" type="text" name="answer_variant" placeholder="Введите вариант ответа">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label>Параметры</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="answer_breack_quiz" value="yes">
                            <label class="form-check-label">
                                Ответ прерывает квиз
                            </label>            
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="answer_correct" value="yes">
                            <label class="form-check-label">
                                Это правильный ответ
                            </label>            
                        </div>                                            
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Сообщение при прерывании</label>
                            <input class="form-control" type="text" name="break_message" placeholder="Введите сообщение">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <button class="btn btn-success" onclick="addQuizBuilder('${container}'); $(this).hide()">Добавить вопрос</button>
    <button class="btn btn-warning" onclick="buildQuizData($(this).parent())">Build</button>
    `;

    $(builder_template).appendTo($(container));
}

function buildQuizData(container) {
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || ' ');
            } else {
                o[this.name] = this.value || ' ';
            }
        });
        return o;
    };
    var quiz_data = [];
    $(container).find('form').each(function (form_i, form) {
        quiz_data.push($(form).serializeObject());
    });

    console.log(quiz_data);
}