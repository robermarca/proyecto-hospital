/* NODOS */

/* PB */

const nodos = {

    ascensor_pb: {
        x: 50.50,
        y: 83.00,
        planta: 0
    },
    cardiologia_pb: {
        x: 3.85,
        y: 83.00,
        planta: 0
    },

    cruce_pb: {
        x: 50.50,
        y: 50.50,
        planta: 0
    },

    fin_pas_izq_pb: {
        x: 4.50,
        y: 50.50,
        planta: 0
    },

    neumologia_pb: {
        x: 4.50,
        y: 61.10,
        planta: 0
    },

    medio_pas_drch_pb: {
        x: 73.00,
        y: 50.50,
        planta: 0
    },

    dermatologia_pb: {
        x: 95.50,
        y: 50.50,
        planta: 0
    },

    hall_2_pb: {
        x: 73.00,
        y: 35.50,
        planta: 0
    },


    radiologia_pb: {
        x: 73.00,
        y: 20.75,
        planta: 0
    },

    oftalmologia_pb: {
        x: 50.50,
        y: 15.50,
        planta: 0
    },
    /* PLANTA 1 */

    ascensor_p1: {
        x: 49.47,
        y: 83.00,
        planta: 1
    },
    otorrino_p1: {
        x: 3.85,
        y: 83.00,
        planta: 1
    },
    
    cruce_p1: {
        x: 50.50,
        y: 50.50,
        planta: 1
    },

    hab117_p1: {
        x: 27.75,
        y: 50.50,
        planta: 1
    },

    hab129_p1: {
        x: 95.75,
        y: 50.50,
        planta: 1
    },

    hab122_p1: {
        x: 50.50,
        y: 2.85,
        planta: 1
    },
/* PLANTA 2 */
    ascensor_p2: {
        x: 49.47,
        y: 83.00,
        planta: 2
    },
    
    cruce_p2: {
        x: 50.50,
        y: 50.50,
        planta: 2
    },

    endoscopias_p2: {
        x: 95.00,
        y: 83.00,
        planta: 2
    },

    hab224_p2: {
        x: 3.65,
        y: 50.50,
        planta: 2
    },

    hab229_p2: {
        x: 50.50,
        y: 20.00,
        planta: 2
    },

    hab237_p2: {
        x: 85.71,
        y: 50.50,
        planta: 2
    }

};



/* CONEXIONES */

/* PB */

const conexiones = {
    ascensor_pb: ["cardiologia_pb", "cruce_pb","ascensor_p1"],

    cardiologia_pb: ["ascensor_pb"],

    cruce_pb: ["ascensor_pb", "fin_pas_izq_pb", "medio_pas_drch_pb", "oftalmologia_pb" ] ,

    fin_pas_izq_pb: ["neumologia_pb", "cruce_pb"] ,

    neumologia_pb: ["fin_pas_izq_pb"] ,

    medio_pas_drch_pb: ["cruce_pb", "dermatologia_pb", "hall_2_pb" ] ,

    dermatologia_pb: ["medio_pas_drch_pb"],

    hall_2_pb: ["medio_pas_drch_pb", "radiologia_pb"],


    radiologia_pb: ["hall_2_pb"],

    oftalmologia_pb: ["cruce_pb"],

    /* PLANTA 1 */

    ascensor_p1: ["otorrino_p1", "cruce_p1","ascensor_p2", "ascensor_pb"],

    otorrino_p1: ["ascensor_p1"],
    
    cruce_p1: ["ascensor_p1", "hab117_p1","hab129_p1", "hab122_p1"],

    hab117_p1: ["cruce_p1"],

    hab129_p1: ["cruce_p1"],

    hab122_p1: ["cruce_p1"],

    /* PLANTA 2 */

    ascensor_p2: ["cruce_p2","endoscopias_p2","ascensor_p1"],
    
    cruce_p2: ["ascensor_p2", "hab224_p2", "hab229_p2", "hab237_p2"],

    endoscopias_p2: ["ascensor_p2"],

    hab224_p2: ["cruce_p2"],

    hab229_p2: ["cruce_p2"],

    hab237_p2: ["cruce_p2"]

};

