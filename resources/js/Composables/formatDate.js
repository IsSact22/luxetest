import moment from "moment";

export const useDateFormatter = () => {
    const formattedDate = (string) => {
        return string ? moment(string).format("DD-MM-YYYY") : "";
    };

    const formattedDateTime = (string) => {
        return string ? moment(string).format("DD-MM-YYYY") : "";
    };

    return { formattedDate, formattedDateTime };
};
