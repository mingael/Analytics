-- Main
create table collect (
    idx integer not null auto_increment comment '일련번호',
    save_date date comment '저장일자',
    ip character varying(15) comment 'IP',
    primary key (idx)
)
comment = '사용자정보수집';

-- Browser 정보
create table collect_browser (
    collect_idx integer not null comment 'collect 일련번호',
    app_time integer not null comment '접속일시',
    app_type character varying(1) comment '접속기기',
    code_name character varying(30) comment '브라우저 코드명',
    name character varying(30) comment '브라우저명',
    version text comment '브라우저 버전',
    platform character varying(15) comment 'OS',
    product character varying(20) comment '엔진',
    user_agent text comment '운영체제 정보'
)
comment = '사용자정보수집-접속정보';

-- Location 페이지 정보
create table collect_location (
    collect_idx integer not null comment 'collect 일련번호',
    app_time integer not null comment '접속일시',
    language_type character varying(2) comment '언어종류',
    language character varying(5) comment '언어',
    protocol character varying(5) comment '프로토콜',
    host character varying(200) comment '방문페이지',
    url character varying(255) not null comment '전체주소',
    param text comment '파라미터'
)
comment = '사용자정보수집-페이지';