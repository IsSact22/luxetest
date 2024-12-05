import moment from "moment-timezone";

export const useDateFormatter = () => {
    const formattedDate = (string) => {
        return string ? moment(string).format("DD-MM-YYYY") : "";
    };

    const formattedDateTime = (string) => {
        return string ? moment(string).format("DD-MM-YYYY HH:mm") : "";
    };

    const currentDateInCaracasTimezoneVE = () => {
        return moment.tz("America/Caracas").format("DD-MM-YYYY");
    };

    const currentDateInCaracasTimezoneInt = () => {
        return moment.tz("America/Caracas").format("YYYY-MM-DD");
    };

    return {
        formattedDate,
        formattedDateTime,
        currentDateInCaracasTimezoneVE,
        currentDateInCaracasTimezoneInt,
    };
};
