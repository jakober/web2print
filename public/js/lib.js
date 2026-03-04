function CMYKtoRGB(c, m, y, k) {
    if (m === undefined) {
        var digits = /cmyk\((\d+),(\d+),(\d+),(\d+)\)/.exec(c);
        if (digits === null) {
            return null;
        }
        c = digits[1];
        m = digits[2];
        y = digits[3];
        k = digits[4];
    }

    c = c / 100;
    m = m / 100;
    y = y / 100;
    k = k / 100;

    var c1 = 1 - c,
            m1 = 1 - m,
            y1 = 1 - y,
            k1 = 1 - k, r, g, b, x;

    //                        C M Y K
    x = c1 * m1 * y1 * k1; // 0 0 0 0
    r = g = b = x;
    x = c1 * m1 * y1 * k;  // 0 0 0 1
    r += 0.1373 * x;
    g += 0.1216 * x;
    b += 0.1255 * x;
    x = c1 * m1 * y * k1; // 0 0 1 0
    r += x;
    g += 0.9490 * x;
    x = c1 * m1 * y * k;  // 0 0 1 1
    r += 0.1098 * x;
    g += 0.1020 * x;
    x = c1 * m * y1 * k1; // 0 1 0 0
    r += 0.9255 * x;
    b += 0.5490 * x;
    x = c1 * m * y1 * k;  // 0 1 0 1
    r += 0.1412 * x;
    x = c1 * m * y * k1; // 0 1 1 0
    r += 0.9294 * x;
    g += 0.1098 * x;
    b += 0.1412 * x;
    x = c1 * m * y * k;  // 0 1 1 1
    r += 0.1333 * x;
    x = c * m1 * y1 * k1; // 1 0 0 0
    g += 0.6784 * x;
    b += 0.9373 * x;
    x = c * m1 * y1 * k;  // 1 0 0 1
    g += 0.0588 * x;
    b += 0.1412 * x;
    x = c * m1 * y * k1; // 1 0 1 0
    g += 0.6510 * x;
    b += 0.3137 * x;
    x = c * m1 * y * k;  // 1 0 1 1
    g += 0.0745 * x;
    x = c * m * y1 * k1; // 1 1 0 0
    r += 0.1804 * x;
    g += 0.1922 * x;
    b += 0.5725 * x;
    x = c * m * y1 * k;  // 1 1 0 1
    b += 0.0078 * x;
    x = c * m * y * k1; // 1 1 1 0
    r += 0.2118 * x;
    g += 0.2119 * x;
    b += 0.2235 * x;

    r = Math.round(r * 255);
    g = Math.round(g * 255);
    b = Math.round(b * 255);

    var r = b + 256 * (g + 256 * r);
    r = '00000' + r.toString(16);
    return '#' + r.substr(r.length - 6);
}
;
