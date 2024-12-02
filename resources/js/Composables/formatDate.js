import moment from "moment";

export const useDateFormatter = () => {
    const formattedDate = (string) => {
        return moment(string).format("DD-MM-YYYY");
    };

    const formattedDateTime = (string) => {
        return moment(string).format("DD-MM-YYYY HH:mm");
    };

    return { formattedDate, formattedDateTime };
};
