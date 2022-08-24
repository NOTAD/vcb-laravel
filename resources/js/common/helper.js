import _ from 'lodash';

export const Helper = {

    convertToLatin(str) {
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        // Some system encode vietnamese combining accent as individual utf-8 characters
        str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, "");
        str = str.replace(/\u02C6|\u0306|\u031B/g, ""); // Â, Ê, Ă, Ơ, Ư
        return str;
    },

    formatSlug(str) {
        str = str.trim();
        str = Helper.convertToLatin(str);
        // eslint-disable-next-line no-useless-escape
        str = str.replace(/[&\/\\#^,+()$~%.\-'":*?<>{}]/g, '');
        str = str.replace(/\s/g, '-');
        return str;
    },

    convertJson(str) {
        if (str !== "" && str != null && typeof (str) !== "undefined") {
            try {
                return JSON.parse(str.replace(/&quot;/g, '"'));
            } catch (err) {
                return str;
            }
        } else {
            return null;
        }
    },

    formatMoney(money) {
        return money.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    },

    strLimit(str, length, ending) {
        if (length == null) {
            length = 100;
        }
        if (ending == null) {
            ending = '...';
        }
        if (str.length > length) {
            return str.substring(0, length - ending.length) + ending;
        } else {
            return str;
        }
    },

    inputMoneyToInt(input) {
        let number = _.clone(input).replace(/[.,"]/g, '');
        return parseInt(number);
    },

    randomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }

        return color;
    },

    removeNullAtrribute(data) {
        _.forEach(data, (item, key) => {
            if (item === null) {
               delete data[key];
            }
        });

        return data;
    }
};
