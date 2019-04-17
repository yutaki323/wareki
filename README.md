# wareki

日付文字列を元号＋和暦数字に変換するクラスです。
(令和対応)

旧システムでも使えるようPHP5でも動作するように書きました。

## usage

### 元号

```
$wareki = new Wareki('2019-04-30');
echo $wareki->format('{gengou}{nendo}年');
```
> 平成31年

```
$wareki = new Wareki('2019-04-30');
echo $wareki->format('{gengou.short}.{nendo}');
```
> H.31

### 年度を漢数字にする

```
$wareki = new Wareki('2019-04-30');
echo $wareki->format('{gengou}{nendo.kanji}年');
```
> 平成三十一年

```
$wareki = new Wareki('2019-05-01');
echo $wareki->format('{gengou}{nendo.kanji}年');
```
> 令和元年

```
$wareki = new Wareki('2019-05-01');
echo $wareki->format('{gengou}の{nendo.kansuji}年');
```
> 令和の一年