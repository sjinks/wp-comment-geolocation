PHP_BACK       := $(wildcard ../inc/*.php)
DOMAIN_BACK    := wwipgeoc
PO_BACK_FILES  := $(wildcard $(DOMAIN_BACK)-*.po)
MO_BACK_FILES  := $(patsubst %.po,%.mo,$(PO_BACK_FILES))

POT_BACK       := $(DOMAIN_BACK).pot

XGETTEXT       := xgettext
MSGFMT         := msgfmt
MSGMERGE       := msgmerge

XGETTEXT_OPTIONS := --language=php \
	--keyword=__ \
	--keyword=_e \
	--keyword=_x:1,2c \
	--keyword=_ex:1,2c \
	--keyword=_n:1,2 \
	--keyword=_nx:1,2,4c \
	--keyword=_n_noop:1,2 \
	--keyword=_nx_noop:1,2,3c \
	--keyword=esc_html__ \
	--keyword=esc_html_e \
	--keyword=esc_html_x:1,2c \
	--keyword=esc_attr__ \
	--keyword=esc_attr_e \
	--keyword=esc_attr_x:1,2c \
	--from-code=UTF-8 --omit-header

.PHONY: all clean

all: $(POT_BACK) $(MO_BACK_FILES)

$(MO_BACK_FILES):  $(PO_BACK_FILES)

%.mo: %.po
	$(MSGFMT) -o$@ $<

$(PO_BACK_FILES): %: $(POT_BACK)
	$(MSGMERGE) -U "$@" $(POT_BACK)
	touch "$@"

$(POT_BACK): $(PHP_BACK)
	$(XGETTEXT) --default-domain=$(DOMAIN_BACK) $(XGETTEXT_OPTIONS) $(PHP_BACK) --output="$@"

clean:
	-rm -f $(POT_BACK) $(MO_BACK_FILES)
