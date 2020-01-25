export let iDB = {
    version: "0.1.0",

    settings: {
        dbName: "idb_db",
        fileindex: ["name.last", "name.first"]
    },

    configure: options => {
        var key, value;
        for (key in options) {
            value = options[key];
            if (value !== undefined && options.hasOwnProperty(key))
                iDB.settings[key] = value;
        }
        return this;
    },

    init: () => {
        var indexedDB =
            window.indexedDB ||
            window.mozIndexedDB ||
            window.webkitIndexedDB ||
            window.msIndexedDB ||
            window.shimIndexedDB;

        var openDB = indexedDB.open(iDB.settings.dbName, 1);

        openDB.onupgradeneeded = function() {
            // var db = {};
            // db.result = openDB.result;
            // db.store = db.result.createObjectStore("MyObjectStore", {
            //     keyPath: "id"
            // });
            // if (iDB.settings.fileindex)
            //     db.index = db.store.createIndex(
            //         "NameIndex",
            //         iDB.settings.fileindex
            //     );
        };

        openDB.onerror = function() {
            console.log(openDB.result.errorCode);
            return false;
        };

        console.log(openDB);

        return openDB;
    },

    createStore: storeName => {
        var db = {};
        db.result = iDB.open().result;
        db.store = db.result.createObjectStore("MyObjectStore", {
            keyPath: "id"
        });
        if (iDB.settings.fileindex)
            db.index = db.store.createIndex(
                "NameIndex",
                iDB.settings.fileindex
            );
    },

    getStore: storeName => {
        var db = {};
        db.result = iDB.open().result;
        db.tx = db.result.transaction("MyObjectStore", "readwrite");
        db.store = db.tx.objectStore("MyObjectStore");
        db.index = db.store.index("NameIndex");

        return db;
    },

    saveToStore: (id, data, fileindex) => {
        var openDB = iDB.open(fileindex);

        openDB.onsuccess = function() {
            var db = iDB.getStore(openDB);

            db.store.put({ id: id, data: data });
        };

        return true;
    },

    findFromStore: (filesearch, callback) => {
        return iDB.load(null, callback, filesearch);
    },

    loadStore: (id, callback, filesearch) => {
        var openDB = iDB.open();

        openDB.onsuccess = function() {
            var db = iDB.getStore(openDB);

            var getData;
            if (id) {
                getData = db.store.get(id);
            } else {
                getData = db.index.get(filesearch);
            }

            getData.onsuccess = function() {
                callback(getData.result.data);
            };

            db.tx.oncomplete = function() {
                db.result.close();
            };
        };

        return true;
    },

    example: () => {
        return iDB.open();
    }
};
