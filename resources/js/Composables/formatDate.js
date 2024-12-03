import moment from "moment-timezone";

export const useDateFormatter = () => {
    const formattedDate = (string) => {
        return string ? moment(string).format("DD-MM-YYYY") : "";
    };

    const formattedDateTime = (string) => {
        return string ? moment(string).format("DD-MM-YYYY HH:mm") : "";
    };

    const currentDateInCaracasTimezone = () => {
        return moment.tz("America/Caracas").format("DD-MM-YYYY");
    };

    return { formattedDate, formattedDateTime, currentDateInCaracasTimezone };
};
