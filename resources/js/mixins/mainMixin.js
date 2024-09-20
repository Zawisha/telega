export default {
    data() {
        return {
        }
    },
    methods: {
        goToLink(url) {
            window.location.href = url;
        },
        errorsToString(errorsObj)
        {
            let errors = errorsObj;
            let errorMessages = '';
            // Проходим по объекту ошибок и добавляем их в одну строку
            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    errorMessages += errors[key].join(' ') + '\n'; // Объединяем массив сообщений в одну строку
                }
            }
            return errorMessages; // Возвращаем строку с ошибками
        },
         addSymbIfNeeded(str, symb) {
        if (str.charAt(0) !== symb) {
            return symb + str;
        }
        return str;
        },

    },
}
