--
-- PostgreSQL database dump
--

\restrict 7mlEuEoPuwRNmVmfszUw5lHgSpJHwO9pxYCKlzxFQYL5CLvlODezd7cZmcY9F8n

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

-- Started on 2026-06-19 19:36:22

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 225 (class 1259 OID 16504)
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16515)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16577)
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id bigint NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16576)
-- Name: categorias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categorias_id_seq OWNER TO postgres;

--
-- TOC entry 5111 (class 0 OID 0)
-- Dependencies: 232
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- TOC entry 231 (class 1259 OID 16557)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16556)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5112 (class 0 OID 0)
-- Dependencies: 230
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 229 (class 1259 OID 16542)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16527)
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16526)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5113 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 220 (class 1259 OID 16459)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16458)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 5114 (class 0 OID 0)
-- Dependencies: 219
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 223 (class 1259 OID 16483)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 16588)
-- Name: productos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.productos (
    id bigint NOT NULL,
    nombre character varying(150) NOT NULL,
    precio numeric(10,2) NOT NULL,
    stock integer DEFAULT 0 NOT NULL,
    categoria_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.productos OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 16587)
-- Name: productos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.productos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.productos_id_seq OWNER TO postgres;

--
-- TOC entry 5115 (class 0 OID 0)
-- Dependencies: 234
-- Name: productos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.productos_id_seq OWNED BY public.productos.id;


--
-- TOC entry 224 (class 1259 OID 16492)
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16469)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(100) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16468)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5116 (class 0 OID 0)
-- Dependencies: 221
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4906 (class 2604 OID 16580)
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- TOC entry 4904 (class 2604 OID 16560)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4903 (class 2604 OID 16530)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4901 (class 2604 OID 16462)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4907 (class 2604 OID 16591)
-- Name: productos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos ALTER COLUMN id SET DEFAULT nextval('public.productos_id_seq'::regclass);


--
-- TOC entry 4902 (class 2604 OID 16472)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 5095 (class 0 OID 16504)
-- Dependencies: 225
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- TOC entry 5096 (class 0 OID 16515)
-- Dependencies: 226
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 5103 (class 0 OID 16577)
-- Dependencies: 233
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorias (id, nombre, descripcion, created_at, updated_at) FROM stdin;
1	Electrónica	Dispositivos electrónicos y accesorios	2026-06-19 17:25:05	2026-06-19 17:25:05
2	Ropa	Prendas de vestir para todas las edades	2026-06-19 17:25:05	2026-06-19 17:25:05
3	Hogar	Artículos para el hogar y decoración	2026-06-19 17:25:05	2026-06-19 17:25:05
4	Deportes	Equipamiento deportivo y accesorios	2026-06-19 17:25:05	2026-06-19 17:25:05
\.


--
-- TOC entry 5101 (class 0 OID 16557)
-- Dependencies: 231
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 5099 (class 0 OID 16542)
-- Dependencies: 229
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 5098 (class 0 OID 16527)
-- Dependencies: 228
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 5090 (class 0 OID 16459)
-- Dependencies: 220
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2024_01_02_000000_create_categorias_table	1
5	2024_01_03_000000_create_productos_table	1
\.


--
-- TOC entry 5093 (class 0 OID 16483)
-- Dependencies: 223
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5105 (class 0 OID 16588)
-- Dependencies: 235
-- Data for Name: productos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.productos (id, nombre, precio, stock, categoria_id, created_at, updated_at) FROM stdin;
1	Smartphone Samsung Galaxy	599.99	25	1	2026-06-19 17:25:05	2026-06-19 17:25:05
2	Laptop HP Pavilion	899.50	15	1	2026-06-19 17:25:05	2026-06-19 17:25:05
3	Auriculares Bluetooth Sony	149.99	50	1	2026-06-19 17:25:05	2026-06-19 17:25:05
4	Camiseta Nike Básica	29.99	100	2	2026-06-19 17:25:05	2026-06-19 17:25:05
5	Jeans Levi's 501	89.99	40	2	2026-06-19 17:25:05	2026-06-19 17:25:05
6	Zapatillas Adidas Ultraboost	179.99	30	2	2026-06-19 17:25:05	2026-06-19 17:25:05
7	Lámpara de Mesa IKEA	45.00	60	3	2026-06-19 17:25:05	2026-06-19 17:25:05
8	Juego de Sábanas 300 Hilos	59.99	35	3	2026-06-19 17:25:05	2026-06-19 17:25:05
9	Balón de Fútbol Adidas	39.99	70	4	2026-06-19 17:25:05	2026-06-19 17:25:05
10	Mancuernas Ajustables	129.99	20	4	2026-06-19 17:25:05	2026-06-19 17:25:05
\.


--
-- TOC entry 5094 (class 0 OID 16492)
-- Dependencies: 224
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
PWTlE2S5YDwiqWM2rcb0aP6jYC529k6wBaiqpIOf	2	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0	ZXlKcGRpSTZJblZFYlc5cWJEVTNTVWcyZDJWNmVtOVJTbGgwVjFFOVBTSXNJblpoYkhWbElqb2lkR2xGSzNWVVZucElSR0kwZHpaSEt6SjJUV0k1TDJGa1drdGxMMjFLUXpJMVNXVjJWVlJvY3lzelYyVTBWRkUzYzJaamJWVnFZMUUwYURkak1VUlphMjFoWmpBNVFYUkdSbkJzTDNoVFdVZFljRzg0U0hKQ2NEWXplalJKYWt4dGRVWnhRM1E0UW01S0x6VnVaVkIxVFRsRGRURlNXVnBSZFhWWlVYSTFhMmt5U1d4dVNrdFhkRWQ2WWpKNFJXaFVNRTAwWW5WdGFTOVhVMUpNTW14aWJubElPQzlFZDNBelRHSXJZMGxYWW5sWVlqRTBjU3RCUkdwR1IyMXdaa2c0VmpOTWIzUTVUSGg1VGt0c05tdFJkM1pVTVdZdkszVjZUVFpXVlZWVGFUVTRSalJ2TlZCWFFtTnFaM0JsTjBreFZYWkVaV2xJU1N0R2RXSTFjMjR5VEU1V2JWUm9SVWgxWjA5RGJrcEtlWEJyUjNsc2QyUnBiREZQVVdoWVoyRk1kR2xwT0hscFdYQkJka3RRV0ZORWIwczBVSFpUVnl0VWRTOUhhbXRuYTBJaUxDSnRZV01pT2lKbFpXTmhNR1UwWTJZeE5UUmpaV1ZtTldNeE9XSmtZalZqWkdJMk1qWmlNVGhpTm1Ga09HVTVOR1UwTVdZMFltUm1OakEwWkRJNU9ETXdZMlkwTkdZNUlpd2lkR0ZuSWpvaUluMD0=	1781914974
z1QmQMEJw9PnRovml4ZhYHI9tYlzoXAS1EhFKQA9	2	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.1 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36	ZXlKcGRpSTZJa1ZhZFd3NVVWUkdXbFpSYkU4MFNGQk5aSG8wVVVFOVBTSXNJblpoYkhWbElqb2lLemhSYVU5WEt6bHlTR1J3V21WSFJXOW1iVk50WlhwcVRYUlNXR05TYjFaSVVUZGlhV1pDZGt4U1NYRkdLMEl3Um5WSGVXTjFielZFV0UxMFZ5OTVXR2xzU3pGbmVFSmhWMHAwTlhsaFFUWklOelUwYXpKRk1GTnZlamgxY1dkVGNXVmhUVGw1WlRaV1pqSmpZV2t6U0ZONFRXWktiMEl4T0c1a1V6ZENlbWxMU2s1M1ZsTnhSbFZEVlhZNFNHMTZURGswYUVaVE1rWkhaVmhpWWpWNmRsa3lSVXRUYlhsNmNrZFNiakZRY0RKTVl5OXBUWGh1Tmsxd2NXdHNNMmwzVWxNMlRrVnNTbWs0UkVaclNIZFhUbFk1TmtkSVNuZFVlREkyYTJGd1RtazBZMVoxYzJSNlVVVkRVWEJvVm5kQ2NtdHBPWEJUUVZwTWEzQlZXbFZ3T0UwMFJ5dExha1E0Tm1OWGNYSmxSRXRKUkdKUmRXNDRkRzE0UkdoclNFNHJhblZKWVcwMk1VZHBRWE05SWl3aWJXRmpJam9pWVdZM00yRXdOVEExWm1Ga01UUTBNbVEzWlRBMk5UazBOMll3WXpobU9XVXhaREUyWWpBd01UQXdZVFZtTkdSa1pETTBNbVl4T1dSbE1tWmlZV1l6WXlJc0luUmhaeUk2SWlKOQ==	1781914910
ZgoKQC0kd9CjIk5ZEuZvA3xfESJ9fQNoHpsSgnMF	2	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.1 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36	ZXlKcGRpSTZJbUU1WjBOQ2RUVnFNamxGUW1saVZtSTNaVkJTV21jOVBTSXNJblpoYkhWbElqb2lWblpqWWxKbVdETXllRWMwT1c5SlJESkZVbGxPZW10QlpqRk9RMWsyVkZRd1RWVk1iV3RMWVU5eVVIYzFPVlZEY1dSR0t6RjBUR3QxY1hFM1dWZzFabHBDTkhwbU9ESk5iMnRSVUdkQkwyVTNMMjEzYkZJdlFreGhURVZsYzFKQlkyZFVNbVExWVdJeVJuUnpUbmczZG05dlNqTm5UMWg1UVhsUFlVRTFUa0ZOY21aTFJHZE9lVEJaZVZoTVVFRXhVMUZsTldKVGQzVjFhRE5OY0hwcmJqaGtlV2hJYkVSUmJtY3laVEpTSzNsVllsZE5VVXBVUWpGc1pIcE1iVEpMVTFoSVVESk5USHA0VEhoUFVXbzFPVUpyZWl0bFEwWjZVeloxVWxnemFYaHdVWGhPUVN0dk4xRlNaVUZwT1VwTWNHOXhObFpCWWxNeFdFVjNNMmxCYm5wck5DOTVjblpMZWxsWk56UjFiVkU1WmsxNmNqTnJObVZpTkhvd2JraGlPV3hhWlVFM2NFNVhLekp6UzB0aVkwVm5RVVJsTWxkbFpUQnZPVlpEZFVzaUxDSnRZV01pT2lJd00yWXhPVGhtTnpJME16STVaalk0TUdWaFl6TTNZVEV6TW1OaFpUVTNNakUzWXpZM1pXSXpPVEpsTkRaa09XRmlNR0k1TVRBMlpUWmhPREU1TlRJeklpd2lkR0ZuSWpvaUluMD0=	1781914920
\.


--
-- TOC entry 5092 (class 0 OID 16469)
-- Dependencies: 222
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
1	Admin	admin@example.com	\N	$argon2id$v=19$m=65536,t=4,p=1$SS81bmYuOS9PSEtaWEYuUw$Wc0YsPCFAVsNh02deoijOBikiFk0BT/dv0//qTVtBnU	\N	2026-06-19 17:25:05	2026-06-19 17:25:05
2	bryan JAvier	programobj@gmail.com	\N	$argon2id$v=19$m=65536,t=4,p=1$ekNwRlEzSWlSU0laTEp2aw$ej0fGXeEZpXEiZqCN296oNH2cxUWLHn/3BQTyZZrztE	\N	2026-06-19 17:54:11	2026-06-19 17:54:11
\.


--
-- TOC entry 5117 (class 0 OID 0)
-- Dependencies: 232
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 4, true);


--
-- TOC entry 5118 (class 0 OID 0)
-- Dependencies: 230
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5119 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5120 (class 0 OID 0)
-- Dependencies: 219
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 5, true);


--
-- TOC entry 5121 (class 0 OID 0)
-- Dependencies: 234
-- Name: productos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.productos_id_seq', 10, true);


--
-- TOC entry 5122 (class 0 OID 0)
-- Dependencies: 221
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- TOC entry 4926 (class 2606 OID 16524)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 4923 (class 2606 OID 16513)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 4938 (class 2606 OID 16586)
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- TOC entry 4934 (class 2606 OID 16572)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4936 (class 2606 OID 16575)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4931 (class 2606 OID 16555)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 4928 (class 2606 OID 16540)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4910 (class 2606 OID 16467)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4916 (class 2606 OID 16491)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 4940 (class 2606 OID 16599)
-- Name: productos productos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT productos_pkey PRIMARY KEY (id);


--
-- TOC entry 4919 (class 2606 OID 16501)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 4912 (class 2606 OID 16623)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4914 (class 2606 OID 16480)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4921 (class 1259 OID 16514)
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- TOC entry 4924 (class 1259 OID 16525)
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- TOC entry 4932 (class 1259 OID 16573)
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- TOC entry 4929 (class 1259 OID 16541)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 4917 (class 1259 OID 16503)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 4920 (class 1259 OID 16502)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 4941 (class 2606 OID 16600)
-- Name: productos productos_categoria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT productos_categoria_id_foreign FOREIGN KEY (categoria_id) REFERENCES public.categorias(id) ON DELETE CASCADE;


-- Completed on 2026-06-19 19:36:22

--
-- PostgreSQL database dump complete
--

\unrestrict 7mlEuEoPuwRNmVmfszUw5lHgSpJHwO9pxYCKlzxFQYL5CLvlODezd7cZmcY9F8n

