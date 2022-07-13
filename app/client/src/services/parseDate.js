const parseDate = (dateString) => {
    let date = '';
    dateString.split(' ')[0].split('-').reverse().forEach((item, index) => date += (index < 2) ? item + '-' : item);
    return date;
}

export default parseDate;