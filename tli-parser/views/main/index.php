<div class="content">
       <form class="form" id="form">
           <div class="form-inner">

               <div class="title">Парсер</div>
               <div class="item">
                   <label>Адрес страницы<span>*</span></label>
                   <div class="error-box-url">Адрес страницы должен быть доступен из сети и может содержать от 4 до 255 символов</div>
                   <input class="inner" required type="text" name="url" id="url" autofocus title="Адрес страницы может содержать от 4 до 255 символов в формате &#34;example.com&#34;"/>
               </div>
               <div class="item">
                   <label>Вид парсинга<span>*</span></label>
                   <div class="error-box-select">Выберите вид парсинга</div>
                   <select class="inner" title="Выберите вид парсинга"name="select" id="select" required>
                       <option value="" class="hidden"></option>
                       <option value="1" id="text">Текст</option>
                       <option value="2" id="links">Ссылки</option>
                       <option value="3" id="images">Картинки</option>
                   </select>
               </div>
               <div class="item">
                   <label class="hidden" id="label">Искомый текст<span>*</span></label>
                   <div class="error-box-textarea">Поле может содержать от 2 до 255 символов</div>
                   <textarea class="inner hidden" minlength="2" name="textarea" id="textarea" placeholder="Вид текста" style="display: none" title="Поле может содержать от 2 до 255 символов русского и латинского алфавита"></textarea>
               </div>
               <input class="inner" type="submit" disabled name="submit" id="submit" value="Парсить"></div>
       </form>

    <div class="result">
        <div class="indent"></div>
        <div id="data-count"></div>
        <div id="data-form"><a href="#" class="btn" id="btn">Загрузать еще</a></div>
    </div>
</div>

<script src="frontend/js/form.js"></script>


