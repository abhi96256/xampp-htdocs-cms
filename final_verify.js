const fs = require('fs');

const oldSql = fs.readFileSync('u615712904_a2p_fixed.sql', 'utf8');   // CLIENT ka purana DB
const newSql = fs.readFileSync('u435351083_cms.sql', 'utf8');          // MERA naya updated DB
const migSql = fs.readFileSync('client_updates_final.sql', 'utf8');    // Migration file

function extractTablesAndCols(sql) {
    const result = {};
    const parts = sql.split('CREATE TABLE `');
    for (let i = 1; i < parts.length; i++) {
        const block = parts[i];
        const idx = block.indexOf('`');
        if (idx === -1) continue;
        const tableName = block.substring(0, idx);
        const startDef = block.indexOf('(');
        const endDef = block.indexOf(') ENGINE=');
        if (startDef !== -1 && endDef !== -1 && startDef < endDef) {
            const inner = block.substring(startDef + 1, endDef);
            const cols = {};
            for (let line of inner.split('\n')) {
                line = line.trim();
                if (line.startsWith('--') || !line) continue;
                const m = line.match(/^`([^`]+)`/);
                if (m) cols[m[1]] = line;
            }
            result[tableName] = cols;
        }
    }
    return result;
}

const oldDB = extractTablesAndCols(oldSql);
const newDB = extractTablesAndCols(newSql);

let allGood = true;
let report = '';

report += '==============================================\n';
report += '  FINAL VERIFICATION REPORT\n';
report += '==============================================\n\n';

// 1. Check new tables
report += '--- NEW TABLES CHECK ---\n';
for (const tb in newDB) {
    if (!oldDB[tb]) {
        // This table should be in migration file
        if (migSql.includes(`CREATE TABLE \`${tb}\``)) {
            report += `  ✅ CREATE TABLE \`${tb}\` — Present in migration file\n`;
        } else {
            report += `  ❌ MISSING! CREATE TABLE \`${tb}\` — NOT in migration file!\n`;
            allGood = false;
        }
    }
}

// 2. Check new columns in existing tables
report += '\n--- NEW COLUMNS CHECK ---\n';
for (const tb in newDB) {
    if (oldDB[tb]) {
        const oldCols = oldDB[tb];
        const newCols = newDB[tb];
        for (const col in newCols) {
            if (!oldCols[col]) {
                // This column should be in migration file
                if (migSql.includes(`ADD \`${col}\``)) {
                    report += `  ✅ ALTER TABLE \`${tb}\` ADD \`${col}\` — Present in migration file\n`;
                } else {
                    report += `  ❌ MISSING! ALTER TABLE \`${tb}\` ADD \`${col}\` — NOT in migration file!\n`;
                    allGood = false;
                }
            }
        }
    }
}

// 3. Final verdict
report += '\n==============================================\n';
if (allGood) {
    report += '  ✅ ALL GOOD! client_updates_final.sql mein kuch bhi miss nahi hai!\n';
} else {
    report += '  ❌ KUCH CHEEZEIN MISS HAIN! Upar dekho.\n';
}
report += '==============================================\n';

console.log(report);
fs.writeFileSync('verification_report.txt', report);
